<?php

namespace App\Filament\Resources\Experiments\Pages;

use App\Enums\ExperimentStatus;
use App\Enums\ScreeningStatus;
use App\Filament\Resources\ExperimentsResource;
use App\Models\Application;
use App\Models\EvaluationExperiment;
use App\Models\ReferenceEvaluation;
use App\Services\Experiment\ExperimentMetricsService;
use App\Services\Screening\ScreeningService;
use Filament\Actions\Action;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;

class ViewExperiment extends ViewRecord
{
    protected static string $resource = ExperimentsResource::class;

    protected function getHeaderActions(): array
    {
        $record = $this->record;

        return [
            Action::make('capture_reference')
                ->label(__('Record Reference Evaluation'))
                ->icon('heroicon-o-clipboard-document-check')
                ->visible(fn (): bool => $record->exists)
                ->form([
                    Select::make('application_id')
                        ->label(__('Application'))
                        ->options($this->availableReferenceApplications())
                        ->required()
                        ->searchable()
                        ->preload(),
                    Textarea::make('criterion_results_json')
                        ->label(__('Criterion Results (JSON array)'))
                        ->rows(8)
                        ->required()
                        ->helperText(__('Provide [{"job_criterion_id": 1, "matched": true}, ...] for all criteria.')),
                    TextInput::make('score')->label(__('Score'))->numeric()->required()->minValue(0)->maxValue(100),
                    Select::make('eligible')->label(__('Eligible'))->options(['1' => __('Yes'), '0' => __('No')])->required()->native(false),
                    Textarea::make('notes')->label(__('Notes'))->nullable()->rows(3),
                    Checkbox::make('is_consolidated')->label(__('Consolidated reference')),
                ])
                ->action(function (array $data) use ($record): void {
                    $criterionResults = json_decode($data['criterion_results_json'], true);

                    if (! is_array($criterionResults)) {
                        return;
                    }

                    ReferenceEvaluation::create([
                        'experiment_id' => $record->id,
                        'application_id' => (int) $data['application_id'],
                        'evaluator_id' => auth()->id(),
                        'criterion_results' => $criterionResults,
                        'score' => (int) $data['score'],
                        'eligible' => (bool) $data['eligible'],
                        'notes' => $data['notes'] ?? null,
                        'is_consolidated' => (bool) ($data['is_consolidated'] ?? false),
                    ]);

                    $this->redirect(static::getResource()::getUrl('view', ['record' => $record]));
                }),
            Action::make('run_analysis')
                ->label(__('Run Controlled Analysis'))
                ->icon('heroicon-o-play')
                ->requiresConfirmation()
                ->visible(fn (): bool => $record->exists)
                ->action(function () use ($record): void {
                    $this->runExperimentAnalysis($record);
                    $this->redirect(static::getResource()::getUrl('view', ['record' => $record]));
                }),
            Action::make('export_csv')
                ->label(__('Export CSV'))
                ->icon('heroicon-o-arrow-down-tray')
                ->url(fn (): string => route('admin.experiments.export', ['experiment' => $record->id]))
                ->openUrlInNewTab()
                ->visible(fn (): bool => $record->runs()->exists()),
        ];
    }

    private function availableReferenceApplications(): array
    {
        $jobs = $this->record->criteria_snapshot['jobs'] ?? [];

        if ($jobs === []) {
            $applications = Application::query()->with('job', 'candidate')->limit(50)->get();
        } else {
            $applications = Application::query()
                ->with('job', 'candidate')
                ->whereIn('job_id', array_values($jobs))
                ->get();
        }

        $options = [];

        foreach ($applications as $application) {
            $options[$application->id] = sprintf('%s — %s (%s)', $application->candidate->name, $application->job->title, $application->id);
        }

        return $options;
    }

    private function runExperimentAnalysis(EvaluationExperiment $experiment): void
    {
        $experiment->forceFill(['status' => ExperimentStatus::Running, 'started_at' => now()])->save();

        $applications = $this->experimentApplications($experiment);

        foreach ($applications as $application) {
            $runs = $experiment->runs()
                ->where('application_id', $application->id)
                ->count();

            $runIndex = $runs + 1;

            $analysis = app(ScreeningService::class)->reanalyze($application);

            $experiment->runs()->create([
                'job_id' => $application->job_id,
                'application_id' => $application->id,
                'screening_analysis_id' => $analysis->id,
                'run_index' => $runIndex,
                'status' => ScreeningStatus::Queued->value,
            ]);
        }

        $experiment->forceFill(['status' => ExperimentStatus::Completed, 'completed_at' => now()])->save();
    }

    private function experimentApplications(EvaluationExperiment $experiment): \Illuminate\Support\Collection
    {
        $snapshotJobs = $experiment->criteria_snapshot['jobs'] ?? [];

        if ($snapshotJobs !== []) {
            return Application::query()
                ->whereIn('job_id', array_values($snapshotJobs))
                ->with(['job', 'candidate'])
                ->get();
        }

        return Application::query()
            ->whereHas('currentAnalysis', fn ($query) => $query->where('status', ScreeningStatus::Completed))
            ->with(['job', 'candidate'])
            ->get();
    }

    public function infolist(Schema $schema): Schema
    {
        return $schema->components([
            Grid::make(2)->schema([
                Section::make(__('Configuration'))->schema([
                    \Filament\Infolists\Components\TextEntry::make('name')->label(__('Name')),
                    \Filament\Infolists\Components\TextEntry::make('dataset_identifier')->label(__('Dataset Identifier')),
                    \Filament\Infolists\Components\TextEntry::make('status')->label(__('Status'))->badge(),
                    \Filament\Infolists\Components\TextEntry::make('model_name')->label(__('Model')),
                    \Filament\Infolists\Components\TextEntry::make('prompt_version')->label(__('Prompt Version')),
                    \Filament\Infolists\Components\TextEntry::make('description')->label(__('Description'))->markdown(),
                ])->columnSpan(1),
                Section::make(__('Execution'))->schema([
                    \Filament\Infolists\Components\TextEntry::make('started_at')->label(__('Started At'))->dateTime()->placeholder('—'),
                    \Filament\Infolists\Components\TextEntry::make('completed_at')->label(__('Completed At'))->dateTime()->placeholder('—'),
                    \Filament\Infolists\Components\TextEntry::make('runs_count')
                        ->label(__('Runs'))
                        ->getStateUsing(fn ($record): int => $record->runs()->count()),
                ])->columnSpan(1),
            ]),
            Tabs::make(__('Experiment'))->schema([
                Tab::make(__('Reference Evaluations'))->schema([
                    \Filament\Infolists\Components\RepeatableEntry::make('referenceEvaluations')
                        ->label(__('Reference Evaluations'))
                        ->schema([
                            \Filament\Infolists\Components\TextEntry::make('application.candidate.name')->label(__('Candidate')),
                            \Filament\Infolists\Components\TextEntry::make('application.job.title')->label(__('Job')),
                            \Filament\Infolists\Components\TextEntry::make('score')->label(__('Score'))->suffix('%'),
                            \Filament\Infolists\Components\TextEntry::make('eligible')->label(__('Eligible'))->formatStateUsing(fn ($state): string => $state ? __('Yes') : __('No'))->color(fn ($state): string => $state ? 'success' : 'danger'),
                            \Filament\Infolists\Components\TextEntry::make('is_consolidated')->label(__('Consolidated'))->formatStateUsing(fn ($state): string => $state ? __('Yes') : __('No')),
                            \Filament\Infolists\Components\TextEntry::make('evaluator.name')->label(__('Evaluator'))->placeholder('—'),
                            \Filament\Infolists\Components\TextEntry::make('notes')->label(__('Notes'))->placeholder('—'),
                        ])
                        ->columns(4),
                ]),
                Tab::make(__('Experiment Runs'))->schema([
                    \Filament\Infolists\Components\RepeatableEntry::make('runs')
                        ->label(__('Experiment Runs'))
                        ->schema([
                            \Filament\Infolists\Components\TextEntry::make('application.candidate.name')->label(__('Candidate')),
                            \Filament\Infolists\Components\TextEntry::make('job.title')->label(__('Job')),
                            \Filament\Infolists\Components\TextEntry::make('run_index')->label(__('Run')),
                            \Filament\Infolists\Components\TextEntry::make('status')->label(__('Status'))->badge(),
                            \Filament\Infolists\Components\TextEntry::make('score')->label(__('Score'))->suffix('%')->placeholder('—'),
                            \Filament\Infolists\Components\TextEntry::make('eligible')->label(__('Eligible'))->formatStateUsing(fn ($state): string => is_null($state) ? '—' : ($state ? __('Yes') : __('No')))->color(fn ($state): string => is_null($state) ? 'gray' : ($state ? 'success' : 'danger')),
                            \Filament\Infolists\Components\TextEntry::make('duration_ms')->label(__('Duration (ms)'))->placeholder('—'),
                            \Filament\Infolists\Components\TextEntry::make('model_name')->label(__('Model'))->placeholder('—'),
                        ])
                        ->columns(4),
                ]),
                Tab::make(__('Summary Metrics'))->schema([
                    \Filament\Infolists\Components\RepeatableEntry::make('metrics')
                        ->getStateUsing(fn ($record): array => app(ExperimentMetricsService::class)->summary($record))
                        ->schema([
                            \Filament\Infolists\Components\TextEntry::make('metric')->label(__('Metric')),
                            \Filament\Infolists\Components\TextEntry::make('value')->label(__('Value')),
                            \Filament\Infolists\Components\TextEntry::make('description')->label(__('Description')),
                        ])
                        ->columns(3),
                ]),
            ]),
        ]);
    }
}
