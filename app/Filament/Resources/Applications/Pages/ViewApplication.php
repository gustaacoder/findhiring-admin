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
use Filament\Support\Colors\Color;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;

class ViewApplication extends ViewRecord
{
    protected static string $resource = ApplicationsResource::class;

    protected function getHeaderActions(): array
    {
        $record = $this->record;

        return [
            Action::make('reanalyze')
                ->label('Reanalyze')
                ->icon('heroicon-o-arrow-path')
                ->requiresConfirmation()
                ->action(function () use ($record): void {
                    app(ScreeningService::class)->reanalyze($record);
                    $this->redirect(static::getResource()::getUrl('view', ['record' => $record]));
                })
                ->visible(fn (): bool => $record->exists),
            Action::make('review')
                ->label('Record Human Review')
                ->icon('heroicon-o-user-check')
                ->form([
                    Select::make('decision')
                        ->options(ReviewDecision::class)
                        ->required()
                        ->enum(ReviewDecision::class)
                        ->native(false),
                    Textarea::make('notes')
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
                Section::make('Candidate')->schema([
                    \Filament\Infolists\Components\TextEntry::make('candidate.name')->label('Name'),
                    \Filament\Infolists\Components\TextEntry::make('candidate.email')->label('Email'),
                    \Filament\Infolists\Components\TextEntry::make('candidate.phone')->label('Phone')->placeholder('—'),
                ])->columnSpan(1),
                Section::make('Job')->schema([
                    \Filament\Infolists\Components\TextEntry::make('job.title')->label('Job'),
                    \Filament\Infolists\Components\TextEntry::make('job.slug')->label('Slug'),
                    \Filament\Infolists\Components\TextEntry::make('submitted_at')->label('Submitted At')->dateTime(),
                ])->columnSpan(1),
                Section::make('Status')->schema([
                    \Filament\Infolists\Components\TextEntry::make('status')->badge(),
                    \Filament\Infolists\Components\TextEntry::make('review_status')->badge(),
                    \Filament\Infolists\Components\TextEntry::make('ranking_position')
                        ->label('Ranking Position')
                        ->getStateUsing(fn ($record): string => (string) (app(CandidateRankingService::class)->rankOf($record) ?? '—')),
                ])->columnSpan(1),
            ]),
            Tabs::make('Application Tabs')->schema([
                Tab::make('AI Analysis')->schema([
                    \Filament\Infolists\Components\TextEntry::make('currentAnalysis.status')->label('Analysis Status')->badge()->visible(fn ($record) => $record->currentAnalysis !== null),
                    \Filament\Infolists\Components\TextEntry::make('currentAnalysis.score')->label('Score')->suffix('%')->visible(fn ($record) => $record->currentAnalysis?->status === ScreeningStatus::Completed),
                    \Filament\Infolists\Components\TextEntry::make('currentAnalysis.eligible')->label('Technically Eligible')->formatStateUsing(fn ($state): string => $state ? 'Yes' : 'No')->color(fn ($state): string => $state ? 'success' : 'danger')->visible(fn ($record) => $record->currentAnalysis?->status === ScreeningStatus::Completed),
                    \Filament\Infolists\Components\TextEntry::make('currentAnalysis.summary')->label('Summary')->markdown()->visible(fn ($record) => filled($record->currentAnalysis?->summary)),
                    \Filament\Infolists\Components\TextEntry::make('currentAnalysis.model_name')->label('Model')->visible(fn ($record) => filled($record->currentAnalysis?->model_name)),
                    \Filament\Infolists\Components\TextEntry::make('currentAnalysis.duration_ms')->label('Processing Time (ms)')->visible(fn ($record) => $record->currentAnalysis?->duration_ms !== null),
                    \Filament\Infolists\Components\TextEntry::make('currentAnalysis.error_code')->label('Error Code')->color('danger')->visible(fn ($record) => filled($record->currentAnalysis?->error_code)),
                ]),
                Tab::make('Criterion Results')->schema([
                    \Filament\Infolists\Components\RepeatableEntry::make('currentAnalysis.criterionResults')
                        ->schema([
                            \Filament\Infolists\Components\TextEntry::make('criterion.name')->label('Criterion'),
                            \Filament\Infolists\Components\TextEntry::make('matched')->label('Matched')->formatStateUsing(fn ($state): string => $state ? 'Yes' : 'No')->color(fn ($state): string => $state ? 'success' : 'danger'),
                            \Filament\Infolists\Components\TextEntry::make('awarded_weight')->label('Awarded Weight (%)'),
                            \Filament\Infolists\Components\TextEntry::make('evidence')->label('Evidence')->markdown(),
                            \Filament\Infolists\Components\TextEntry::make('reason')->label('Reason')->markdown(),
                        ])
                        ->columns(2)
                        ->visible(fn ($record) => $record->currentAnalysis && $record->currentAnalysis->criterionResults()->exists()),
                ]),
                Tab::make('Human Reviews')->schema([
                    \Filament\Tables\Table::make()
                        ->relationship('humanReviews')
                        ->columns([
                            TextColumn::make('reviewer.name')->label('Reviewer'),
                            TextColumn::make('decision')->badge(),
                            TextColumn::make('notes')->limit(60),
                            TextColumn::make('reviewed_at')->dateTime(),
                        ])
                        ->recordActions([])
                        ->toolbarActions([]),
                ]),
                Tab::make('Processing History')->schema([
                    \Filament\Tables\Table::make()
                        ->relationship('screeningAnalyses')
                        ->columns([
                            TextColumn::make('status')->badge(),
                            TextColumn::make('score')->suffix('%'),
                            TextColumn::make('eligible')->formatStateUsing(fn ($state): string => is_null($state) ? '—' : ($state ? 'Yes' : 'No'))->color(fn ($state): string => is_null($state) ? 'gray' : ($state ? 'success' : 'danger')),
                            TextColumn::make('is_current')->label('Current')->boolean(),
                            TextColumn::make('duration_ms')->label('Duration (ms)'),
                            TextColumn::make('error_code')->label('Error'),
                            TextColumn::make('created_at')->dateTime()->label('Created'),
                            TextColumn::make('completed_at')->dateTime()->label('Completed'),
                        ])
                        ->recordActions([])
                        ->toolbarActions([]),
                ]),
                Tab::make('Résumé')->schema([
                    \Filament\Infolists\Components\TextEntry::make('original_filename')->label('Original Filename'),
                    \Filament\Infolists\Components\TextEntry::make('resume_mime_type')->label('MIME Type'),
                    \Filament\Infolists\Components\TextEntry::make('resume_size_bytes')->label('Size (bytes)'),
                    \Filament\Infolists\Components\TextEntry::make('resume_fingerprint')->label('SHA256')->limit(32)->copyable(),
                    \Filament\Infolists\Components\Actions::make([
                        Action::make('download_resume')
                            ->label('Download Original Résumé')
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
