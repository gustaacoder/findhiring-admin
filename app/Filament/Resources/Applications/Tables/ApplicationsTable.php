<?php

namespace App\Filament\Resources\Applications\Tables;

use App\Enums\ApplicationStatus;
use App\Enums\ReviewStatus;
use App\Models\Application;
use App\Services\Ranking\CandidateRankingService;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ApplicationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('candidate.name')
                    ->label(__('Candidate'))
                    ->searchable(query: fn (Builder $query, string $search): Builder => $query->whereHas('candidate', fn ($builder) => $builder->where('name', 'like', '%'.$search.'%')->orWhere('email', 'like', '%'.$search.'%')))
                    ->sortable(),
                TextColumn::make('job.title')
                    ->label(__('Job'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('status')
                    ->label(__('Status'))
                    ->badge(),
                TextColumn::make('review_status')
                    ->label(__('Review Status'))
                    ->badge(),
                TextColumn::make('currentAnalysis.score')
                    ->label(__('Score'))
                    ->suffix('%')
                    ->placeholder('—'),
                TextColumn::make('currentAnalysis.eligible')
                    ->label(__('Eligible'))
                    ->formatStateUsing(fn ($state): string => is_null($state) ? '—' : ($state ? __('Yes') : __('No')))
                    ->color(fn ($state): string => is_null($state) ? 'gray' : ($state ? 'success' : 'danger')),
                TextColumn::make('ranking_position')
                    ->label(__('Rank'))
                    ->getStateUsing(fn (Application $record): string => (string) (app(CandidateRankingService::class)->rankOf($record) ?? '—')),
                TextColumn::make('submitted_at')
                    ->label(__('Submitted At'))
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('job')
                    ->label(__('Job'))
                    ->relationship('job', 'title')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('status')
                    ->label(__('Status'))
                    ->options(ApplicationStatus::class),
                SelectFilter::make('review_status')
                    ->label(__('Review Status'))
                    ->options(ReviewStatus::class),
                SelectFilter::make('eligible')
                    ->label(__('Eligible'))
                    ->options([
                        '1' => __('Eligible'),
                        '0' => __('Not Eligible'),
                    ])
                    ->query(fn (Builder $query, array $data): Builder => $query
                        ->when(
                            array_key_exists('value', $data) && $data['value'] !== null,
                            fn (Builder $builder): Builder => $builder->whereHas('currentAnalysis', fn ($analysis) => $analysis->where('eligible', (bool) $data['value'])->where('status', \App\Enums\ScreeningStatus::Completed)),
                        )),
            ])
            ->recordActions([
                ViewAction::make(),
            ])
            ->toolbarActions([])
            ->defaultSort('submitted_at', 'desc');
    }
}
