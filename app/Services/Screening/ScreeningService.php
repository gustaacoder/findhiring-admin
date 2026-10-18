<?php

namespace App\Services\Screening;

use App\Enums\ApplicationStatus;
use App\Enums\ScreeningStatus;
use App\Exceptions\ScreeningException;
use App\Jobs\RunScreeningAnalysis;
use App\Models\Application;
use App\Models\JobCriterion;
use App\Models\ScreeningAnalysis;
use App\Services\Audit\AuditLogger;
use Illuminate\Support\Facades\DB;

class ScreeningService
{
    public function __construct(
        private readonly ResumeTextExtractor $resumeTextExtractor,
        private readonly GeminiScreeningClient $geminiClient,
        private readonly ScreeningScoreCalculator $scoreCalculator,
        private readonly AuditLogger $auditLogger,
    ) {}

    public function queueScreening(Application $application): ScreeningAnalysis
    {
        $analysis = $application->screeningAnalyses()->create([
            'status' => ScreeningStatus::Queued,
            'prompt_version' => (string) config('findhiring.gemini.prompt_version', 'v1'),
        ]);

        $this->auditLogger->log('screening.queued', $application, 'Screening analysis #'.$analysis->id.' queued.');

        RunScreeningAnalysis::dispatch($analysis->id);

        return $analysis;
    }

    public function reanalyze(Application $application): ScreeningAnalysis
    {
        $analysis = $application->screeningAnalyses()->create([
            'status' => ScreeningStatus::Queued,
            'prompt_version' => (string) config('findhiring.gemini.prompt_version', 'v1'),
        ]);

        $this->auditLogger->log('screening.reanalysis_requested', $application, 'Reanalysis requested as analysis #'.$analysis->id.'.');

        RunScreeningAnalysis::dispatch($analysis->id);

        return $analysis;
    }

    public function execute(ScreeningAnalysis $analysis): void
    {
        $analysis->refresh();

        if ($analysis->isCompleted()) {
            return;
        }

        $application = $analysis->application()->with(['job.criteria', 'candidate'])->first();

        if ($application === null) {
            return;
        }

        $criteria = $application->job->criteria->values();
        $snapshot = $this->buildCriteriaSnapshot($application);

        $analysis->forceFill([
            'status' => ScreeningStatus::Processing,
            'started_at' => now(),
            'attempts' => $analysis->attempts + 1,
            'model_name' => (string) config('findhiring.gemini.model', 'gemini-2.5-flash'),
            'prompt_version' => (string) config('findhiring.gemini.prompt_version', 'v1'),
            'criteria_snapshot' => $snapshot,
            'error_code' => null,
            'error_message' => null,
        ])->save();

        $application->markAsProcessing();

        $startedAt = microtime(true);

        try {
            $resumePath = storage_path('app/private/'.$application->resume_path);
            $resumeText = $this->resumeTextExtractor->extract($resumePath, $application->resume_mime_type);

            $analysis->forceFill([
                'input_fingerprint' => hash('sha256', $resumeText),
            ])->save();

            $outcome = $this->geminiClient->screen($snapshot, $resumeText);
            $scoreResult = $this->scoreCalculator->calculate($criteria, $outcome->matchedCriterionIds());

            $durationMs = (int) round((microtime(true) - $startedAt) * 1000);

            DB::transaction(function () use ($analysis, $application, $criteria, $outcome, $scoreResult, $durationMs): void {
                $analysis->criterionResults()->delete();

                foreach ($criteria as $criterion) {
                    $criterionOutcome = $outcome->outcomeFor((int) $criterion->id);

                    $analysis->criterionResults()->create([
                        'job_criterion_id' => $criterion->id,
                        'matched' => $criterionOutcome?->matched ?? false,
                        'evidence' => $criterionOutcome?->evidence,
                        'reason' => $criterionOutcome?->reason,
                        'awarded_weight' => $scoreResult->awardedWeightFor((int) $criterion->id),
                    ]);
                }

                $analysis->forceFill([
                    'status' => ScreeningStatus::Completed,
                    'score' => $scoreResult->score,
                    'eligible' => $scoreResult->eligible,
                    'summary' => $outcome->summary,
                    'duration_ms' => $durationMs,
                    'completed_at' => now(),
                    'error_code' => null,
                    'error_message' => null,
                ])->save();

                $application->screeningAnalyses()
                    ->whereKeyNot($analysis->id)
                    ->update(['is_current' => false]);

                $analysis->forceFill(['is_current' => true])->save();
            });

            $application->markAsScreened();
            $this->syncExperimentRuns($analysis);
            $this->auditLogger->log('screening.completed', $application, sprintf(
                'Analysis #%d completed with score %d and eligibility %s.',
                $analysis->id,
                $scoreResult->score,
                $scoreResult->eligible ? 'true' : 'false',
            ));
        } catch (ScreeningException $exception) {
            $durationMs = (int) round((microtime(true) - $startedAt) * 1000);

            $analysis->forceFill([
                'status' => ScreeningStatus::Failed,
                'duration_ms' => $durationMs,
                'completed_at' => now(),
                'error_code' => $exception->errorCode,
                'error_message' => mb_substr($exception->getMessage(), 0, 1000),
            ])->save();

            $this->resolveApplicationStatus($application);
            $this->syncExperimentRuns($analysis);
            $this->auditLogger->log('screening.failed', $application, sprintf(
                'Analysis #%d failed with error code %s.',
                $analysis->id,
                $exception->errorCode,
            ));

            if ($exception->retryable) {
                throw $exception;
            }
        }
    }

    private function buildCriteriaSnapshot(Application $application): array
    {
        return [
            'job_id' => (int) $application->job_id,
            'job_title' => $application->job->title,
            'captured_at' => now()->toIso8601String(),
            'criteria' => $application->job->criteria->map(fn (JobCriterion $criterion): array => [
                'id' => (int) $criterion->id,
                'name' => $criterion->name,
                'description' => $criterion->description,
                'expected_evidence' => $criterion->expected_evidence,
                'type' => $criterion->type->value,
                'weight' => (int) $criterion->weight,
            ])->values()->all(),
        ];
    }

    private function resolveApplicationStatus(Application $application): void
    {
        $hasValidCurrentAnalysis = $application->screeningAnalyses()
            ->where('is_current', true)
            ->where('status', ScreeningStatus::Completed)
            ->exists();

        if ($hasValidCurrentAnalysis) {
            $application->forceFill(['status' => ApplicationStatus::Screened])->save();

            return;
        }

        $application->markAsFailed();
    }

    private function syncExperimentRuns(ScreeningAnalysis $analysis): void
    {
        $analysis->experimentRuns()->update([
            'status' => $analysis->status->value,
            'score' => $analysis->score,
            'eligible' => $analysis->eligible,
            'duration_ms' => $analysis->duration_ms,
            'model_name' => $analysis->model_name,
            'prompt_version' => $analysis->prompt_version,
            'criteria_snapshot' => $analysis->criteria_snapshot,
            'input_fingerprint' => $analysis->input_fingerprint,
            'error_code' => $analysis->error_code,
        ]);
    }
}
