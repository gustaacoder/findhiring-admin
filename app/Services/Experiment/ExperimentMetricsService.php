<?php

namespace App\Services\Experiment;

use App\Enums\ScreeningStatus;
use App\Models\EvaluationExperiment;
use App\Models\ExperimentRun;
use App\Models\ReferenceEvaluation;
use Illuminate\Support\Collection;

class ExperimentMetricsService
{
    public function summary(EvaluationExperiment $experiment): array
    {
        $consolidatedReferences = $this->consolidatedReferences($experiment);
        $completedRuns = $this->completedRuns($experiment);

        $averageDuration = $completedRuns->pluck('duration_ms')->filter()->average();
        $averageDurationSeconds = $averageDuration !== null ? round($averageDuration / 1000, 3) : null;

        $runScores = $completedRuns->pluck('score')->filter();
        $refScores = $consolidatedReferences->pluck('score')->filter();

        $scoreDifferences = $runScores->map(function (int $score) use ($refScores): float {
            if ($refScores->isEmpty()) {
                return 0.0;
            }

            $referenceScore = (int) round($refScores->first(), 0);

            return abs($score - $referenceScore);
        });

        $meanAbsoluteError = $scoreDifferences->isEmpty() ? null : round($scoreDifferences->average(), 2);
        $criterionAgreement = $this->criterionAgreement($experiment);

        return [
            ['metric' => 'Total runs', 'value' => (string) $experiment->runs()->count(), 'description' => 'Number of recorded experimental runs.'],
            ['metric' => 'Completed runs', 'value' => (string) $completedRuns->count(), 'description' => 'Runs that reached a completed state.'],
            ['metric' => 'Failed runs', 'value' => (string) $experiment->runs()->where('status', ScreeningStatus::Failed)->count(), 'description' => 'Runs that terminated with an error.'],
            ['metric' => 'Average processing time', 'value' => $averageDurationSeconds !== null ? ($averageDurationSeconds.' s') : '—', 'description' => 'Mean duration across completed runs.'],
            ['metric' => 'Consolidated references', 'value' => (string) $consolidatedReferences->count(), 'description' => 'Manual reference evaluations marked as consolidated.'],
            ['metric' => 'Criterion agreement', 'value' => $criterionAgreement !== null ? ($criterionAgreement.'%') : '—', 'description' => 'Agreement between current AI results and consolidated reference at criterion level.'],
            ['metric' => 'Mean absolute error (score)', 'value' => $meanAbsoluteError !== null ? (string) $meanAbsoluteError : '—', 'description' => 'Average absolute difference between AI and reference scores.'],
            ['metric' => 'Eligibility agreement', 'value' => $this->eligibilityAgreement($experiment), 'description' => 'Proportion of matched cases by eligibility.'],
        ];
    }

    private function consolidatedReferences(EvaluationExperiment $experiment): Collection
    {
        return $experiment->consolidatedReferences()->with('application')->get();
    }

    private function completedRuns(EvaluationExperiment $experiment): Collection
    {
        return $experiment->runs()
            ->where('status', ScreeningStatus::Completed)
            ->with(['analysis', 'application'])
            ->get();
    }

    private function criterionAgreement(EvaluationExperiment $experiment): ?float
    {
        $pairs = collect();

        $this->consolidatedReferences($experiment)->each(function (ReferenceEvaluation $reference) use ($experiment, $pairs): void {
            $latestRun = $experiment->runs()
                ->where('application_id', $reference->application_id)
                ->where('status', ScreeningStatus::Completed)
                ->latest('run_index')
                ->first();

            if ($latestRun === null || $latestRun->analysis === null) {
                return;
            }

            $refCriteria = collect($reference->criterion_results);
            $aiCriteria = $latestRun->analysis->criterionResults()->get();

            foreach ($refCriteria as $refCriterion) {
                $criterionId = (int) ($refCriterion['job_criterion_id'] ?? 0);
                $aiResult = $aiCriteria->firstWhere('job_criterion_id', $criterionId);

                if ($aiResult === null) {
                    continue;
                }

                $pairs->push([
                    'ref' => (bool) ($refCriterion['matched'] ?? false),
                    'ai' => (bool) $aiResult->matched,
                ]);
            }
        });

        if ($pairs->isEmpty()) {
            return null;
        }

        $agreements = $pairs->filter(fn (array $pair): bool => $pair['ref'] === $pair['ai'])->count();

        return round(($agreements / $pairs->count()) * 100, 2);
    }

    private function eligibilityAgreement(EvaluationExperiment $experiment): string
    {
        $pairs = collect();

        $this->consolidatedReferences($experiment)->each(function (ReferenceEvaluation $reference) use ($experiment, $pairs): void {
            $latestRun = $experiment->runs()
                ->where('application_id', $reference->application_id)
                ->where('status', ScreeningStatus::Completed)
                ->latest('run_index')
                ->first();

            if ($latestRun === null) {
                return;
            }

            $pairs->push([
                'ref' => $reference->eligible,
                'ai' => $latestRun->eligible,
            ]);
        });

        if ($pairs->isEmpty()) {
            return '—';
        }

        $agreements = $pairs->filter(fn (array $pair): bool => $pair['ref'] === $pair['ai'])->count();

        return round(($agreements / $pairs->count()) * 100, 2).'%';
    }
}
