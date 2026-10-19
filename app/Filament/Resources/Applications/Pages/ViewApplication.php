<?php

namespace App\Filament\Resources\Applications\Pages;

use App\Enums\ReviewDecision;
use App\Enums\ReviewStatus;
use App\Enums\ScreeningStatus;
use App\Filament\Resources\ApplicationsResource;
use App\Models\HumanReview;
use App\Services\Ranking\CandidateRankingService;
use App\Services\Screening\ScreeningService;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;

class ViewApplication extends ViewRecord
{
    protected static string $resource = ApplicationsResource::class;

    protected function getHeaderActions(): array
    {
        $record = $this->record;

        return [
            Action::make('reanalyze')
                ->label(__('Reanalyze'))
                ->icon('heroicon-o-arrow-path')
                ->requiresConfirmation()
                ->action(function () use ($record): void {
                    app(ScreeningService::class)->reanalyze($record);
                    $this->redirect(static::getResource()::getUrl('view', ['record' => $record]));
                })
                ->visible(fn (): bool => $record->exists),
            Action::make('review')
                ->label(__('Record Human Review'))
                ->icon('heroicon-o-clipboard-document-check')
                ->form([
                    Select::make('decision')
                        ->label(__('Decision'))
                        ->options(ReviewDecision::class)
                        ->required()
                        ->enum(ReviewDecision::class)
                        ->native(false),
                    Textarea::make('notes')
                        ->label(__('Notes'))
                        ->rows(3)
                        ->nullable(),
                ])
                ->action(function (array $data) use ($record): void {
                    HumanReview::create([
                        'application_id' => $record->id,
                        'reviewer_id' => auth()->id(),
                        'decision' => $data['decision'],
                        'notes' => $data['notes'] ?? null,
                        'reviewed_at' => now(),
                    ]);

                    $record->forceFill(['review_status' => ReviewStatus::Completed])->save();
                    $this->redirect(static::getResource()::getUrl('view', ['record' => $record]));
                })
                ->visible(fn (): bool => $record->exists),
        ];
    }

    public function infolist(Schema $schema): Schema
    {
        return $schema->components([
            Grid::make(3)->schema([
                Section::make(__('Candidate'))->schema([
                    \Filament\Infolists\Components\TextEntry::make('candidate.name')->label(__('Name')),
                    \Filament\Infolists\Components\TextEntry::make('candidate.email')->label(__('Email')),
                    \Filament\Infolists\Components\TextEntry::make('candidate.phone')->label(__('Phone'))->placeholder('—'),
                ])->columnSpan(1),
                Section::make(__('Job'))->schema([
                    \Filament\Infolists\Components\TextEntry::make('job.title')->label(__('Job')),
                    \Filament\Infolists\Components\TextEntry::make('job.slug')->label(__('Slug')),
                    \Filament\Infolists\Components\TextEntry::make('submitted_at')->label(__('Submitted At'))->dateTime(),
                ])->columnSpan(1),
                Section::make(__('Status'))->schema([
                    \Filament\Infolists\Components\TextEntry::make('status')->label(__('Status'))->badge(),
                    \Filament\Infolists\Components\TextEntry::make('review_status')->label(__('Review Status'))->badge(),
                    \Filament\Infolists\Components\TextEntry::make('ranking_position')
                        ->label(__('Ranking Position'))
                        ->getStateUsing(fn ($record): string => (string) (app(CandidateRankingService::class)->rankOf($record) ?? '—')),
                ])->columnSpan(1),
            ]),
            Tabs::make(__('Application Tabs'))->schema([
                Tab::make(__('AI Analysis'))->schema([
                    \Filament\Infolists\Components\TextEntry::make('currentAnalysis.status')->label(__('Analysis Status'))->badge()->visible(fn ($record) => $record->currentAnalysis !== null),
                    \Filament\Infolists\Components\TextEntry::make('currentAnalysis.score')->label(__('Score'))->suffix('%')->visible(fn ($record) => $record->currentAnalysis?->status === ScreeningStatus::Completed),
                    \Filament\Infolists\Components\TextEntry::make('currentAnalysis.eligible')->label(__('Technically Eligible'))->formatStateUsing(fn ($state): string => $state ? __('Yes') : __('No'))->color(fn ($state): string => $state ? 'success' : 'danger')->visible(fn ($record) => $record->currentAnalysis?->status === ScreeningStatus::Completed),
                    \Filament\Infolists\Components\TextEntry::make('currentAnalysis.summary')->label(__('Summary'))->markdown()->visible(fn ($record) => filled($record->currentAnalysis?->summary)),
                    \Filament\Infolists\Components\TextEntry::make('currentAnalysis.model_name')->label(__('Model'))->visible(fn ($record) => filled($record->currentAnalysis?->model_name)),
                    \Filament\Infolists\Components\TextEntry::make('currentAnalysis.duration_ms')->label(__('Processing Time (ms)'))->visible(fn ($record) => $record->currentAnalysis?->duration_ms !== null),
                    \Filament\Infolists\Components\TextEntry::make('currentAnalysis.error_code')->label(__('Error Code'))->color('danger')->visible(fn ($record) => filled($record->currentAnalysis?->error_code)),
                    \Filament\Infolists\Components\TextEntry::make('currentAnalysis.error_message')->label(__('Error Message'))->color('danger')->visible(fn ($record) => filled($record->currentAnalysis?->error_message)),
                ]),
                Tab::make(__('Criterion Results'))->schema([
                    \Filament\Infolists\Components\RepeatableEntry::make('currentAnalysis.criterionResults')
                        ->schema([
                            \Filament\Infolists\Components\TextEntry::make('criterion.name')->label(__('Criterion')),
                            \Filament\Infolists\Components\TextEntry::make('matched')->label(__('Matched'))->formatStateUsing(fn ($state): string => $state ? __('Yes') : __('No'))->color(fn ($state): string => $state ? 'success' : 'danger'),
                            \Filament\Infolists\Components\TextEntry::make('awarded_weight')->label(__('Awarded Weight (%)')),
                            \Filament\Infolists\Components\TextEntry::make('evidence')->label(__('Evidence'))->markdown(),
                            \Filament\Infolists\Components\TextEntry::make('reason')->label(__('Reason'))->markdown(),
                        ])
                        ->columns(2)
                        ->visible(fn ($record) => $record->currentAnalysis && $record->currentAnalysis->criterionResults()->exists()),
                ]),
                Tab::make(__('Human Reviews'))->schema([
                    \Filament\Infolists\Components\RepeatableEntry::make('humanReviews')
                        ->label(__('Human Reviews'))
                        ->schema([
                            \Filament\Infolists\Components\TextEntry::make('reviewer.name')->label(__('Reviewer')),
                            \Filament\Infolists\Components\TextEntry::make('decision')->label(__('Decision'))->badge(),
                            \Filament\Infolists\Components\TextEntry::make('notes')->label(__('Notes'))->placeholder('—'),
                            \Filament\Infolists\Components\TextEntry::make('reviewed_at')->label(__('Reviewed At'))->dateTime(),
                        ])
                        ->columns(4),
                ]),
                Tab::make(__('Processing History'))->schema([
                    \Filament\Infolists\Components\RepeatableEntry::make('screeningAnalyses')
                        ->label(__('Processing History'))
                        ->schema([
                            \Filament\Infolists\Components\TextEntry::make('status')->label(__('Status'))->badge(),
                            \Filament\Infolists\Components\TextEntry::make('score')->label(__('Score'))->suffix('%')->placeholder('—'),
                            \Filament\Infolists\Components\TextEntry::make('eligible')->label(__('Eligible'))->formatStateUsing(fn ($state): string => is_null($state) ? '—' : ($state ? __('Yes') : __('No')))->color(fn ($state): string => is_null($state) ? 'gray' : ($state ? 'success' : 'danger')),
                            \Filament\Infolists\Components\TextEntry::make('is_current')->label(__('Current'))->formatStateUsing(fn ($state): string => $state ? __('Yes') : __('No')),
                            \Filament\Infolists\Components\TextEntry::make('duration_ms')->label(__('Duration (ms)'))->placeholder('—'),
                            \Filament\Infolists\Components\TextEntry::make('error_code')->label(__('Error'))->placeholder('—'),
                            \Filament\Infolists\Components\TextEntry::make('error_message')->label(__('Error Message'))->placeholder('—'),
                            \Filament\Infolists\Components\TextEntry::make('created_at')->dateTime()->label(__('Created')),
                            \Filament\Infolists\Components\TextEntry::make('completed_at')->dateTime()->label(__('Completed'))->placeholder('—'),
                        ])
                        ->columns(4),
                ]),
                Tab::make(__('Résumé'))->schema([
                    \Filament\Infolists\Components\TextEntry::make('original_filename')->label(__('Original Filename')),
                    \Filament\Infolists\Components\TextEntry::make('resume_mime_type')->label(__('MIME Type')),
                    \Filament\Infolists\Components\TextEntry::make('resume_size_bytes')->label(__('Size (bytes)')),
                    \Filament\Infolists\Components\TextEntry::make('resume_fingerprint')->label('SHA256')->limit(32)->copyable(),
                    \Filament\Schemas\Components\Actions::make([
                        Action::make('download_resume')
                            ->label(__('Download Original Résumé'))
                            ->icon('heroicon-o-document-arrow-down')
                            ->url(fn ($record): string => route('admin.applications.resume.download', ['application' => $record->id]))
                            ->openUrlInNewTab()
                            ->visible(fn (): bool => auth()->check()),
                    ]),
                ]),
            ]),
        ]);
    }
}
