<?php

namespace App\Filament\Resources;

use App\Enums\ScreeningStatus;
use App\Models\ScreeningAnalysis;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class ScreeningAnalysesResource extends Resource
{
    protected static ?string $model = ScreeningAnalysis::class;

    protected static BackedEnum|string|null $navigationIcon = Heroicon::Sparkles;

    protected static UnitEnum|string|null $navigationGroup = 'AI Screening';

    public static function getLabel(): ?string
    {
        return __('Analysis');
    }

    public static function getPluralLabel(): ?string
    {
        return __('Screening Analyses');
    }

    public static function getNavigationGroup(): string|UnitEnum|null
    {
        return __('AI Screening');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('application.job.title')->label(__('Job')),
                TextColumn::make('application.candidate.name')->label(__('Candidate'))->searchable(),
                TextColumn::make('status')->label(__('Status'))->badge(),
                TextColumn::make('score')->label(__('Score'))->suffix('%')->placeholder('—'),
                TextColumn::make('eligible')->label(__('Eligible'))->formatStateUsing(fn ($state): string => is_null($state) ? '—' : ($state ? __('Yes') : __('No')))->color(fn ($state): string => is_null($state) ? 'gray' : ($state ? 'success' : 'danger')),
                TextColumn::make('model_name')->label(__('Model'))->toggleable(),
                TextColumn::make('duration_ms')->label(__('Duration (ms)'))->placeholder('—'),
                TextColumn::make('error_code')->label(__('Error'))->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('error_message')->label(__('Error Message'))->limit(80)->tooltip(fn (TextColumn $column): ?string => $column->getState())->toggleable(),
                IconColumn::make('is_current')->boolean()->label(__('Current')),
                TextColumn::make('created_at')->label(__('Created At'))->dateTime()->sortable(),
                TextColumn::make('completed_at')->label(__('Completed At'))->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')->label(__('Status'))->options(ScreeningStatus::class),
                SelectFilter::make('job')->label(__('Job'))->relationship('application.job', 'title')->searchable()->preload(),
                SelectFilter::make('is_current')->label(__('Current'))->options(['1' => __('Current'), '0' => __('Not Current')]),
            ])
            ->recordActions([])
            ->toolbarActions([])
            ->defaultSort('created_at', 'desc')
            ->modifyQueryUsing(fn (Builder $query): Builder => $query->with(['application.job', 'application.candidate']));
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ScreeningAnalyses\Pages\ListScreeningAnalyses::route('/'),
        ];
    }
}
