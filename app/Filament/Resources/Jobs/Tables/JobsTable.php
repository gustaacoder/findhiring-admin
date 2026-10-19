<?php

namespace App\Filament\Resources\Jobs\Tables;

use App\Enums\JobStatus;
use App\Models\Job;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class JobsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label(__('Title'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('slug')
                    ->label(__('Slug'))
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('status')
                    ->label(__('Status'))
                    ->badge()
                    ->color(fn (JobStatus $state): string => match ($state) {
                        JobStatus::Draft => 'gray',
                        JobStatus::Published => 'success',
                        JobStatus::Closed => 'warning',
                        JobStatus::Archived => 'danger',
                    }),
                TextColumn::make('applications_count')
                    ->counts('applications')
                    ->label(__('Applications')),
                TextColumn::make('criteria_count')
                    ->counts('criteria')
                    ->label(__('Criteria')),
                TextColumn::make('published_at')
                    ->label(__('Published At'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('publicUrl')
                    ->state(fn (Job $record): string => $record->publicUrl())
                    ->copyable()
                    ->limit(40)
                    ->label(__('Public URL'))
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_by')
                    ->label(__('Created By'))
                    ->getStateUsing(fn (Job $record): string => $record->creator?->name ?? '—'),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label(__('Status'))
                    ->options(JobStatus::class),
                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
