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
use Filament\Tables\Columns\IconColumn;
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
                    ->searchable()
                    ->sortable(),
                TextColumn::make('slug')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (JobStatus $state): string => match ($state) {
                        JobStatus::Draft => 'gray',
                        JobStatus::Published => 'success',
                        JobStatus::Closed => 'warning',
                        JobStatus::Archived => 'danger',
                    }),
                TextColumn::make('applications_count')
                    ->counts('applications')
                    ->label('Applications'),
                TextColumn::make('criteria_count')
                    ->counts('criteria')
                    ->label('Criteria'),
                TextColumn::make('published_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('publicUrl')
                    ->state(fn (Job $record): string => $record->publicUrl())
                    ->copyable()
                    ->limit(40)
                    ->label('Public URL')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_by')
                    ->getStateUsing(fn (Job $record): string => $record->creator?->name ?? '—'),
            ])
            ->filters([
                SelectFilter::make('status')
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
