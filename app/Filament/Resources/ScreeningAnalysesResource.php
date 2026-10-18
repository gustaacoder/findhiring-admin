<?php

namespace App\Filament\Resources;

use App\Enums\ScreeningStatus;
use App\Models\ScreeningAnalysis;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
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
        return 'Analysis';
    }

    public static function getPluralLabel(): ?string
    {
        return 'Screening Analyses';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('application.job.title')->label('Job'),
                TextColumn::make('application.candidate.name')->label('Candidate')->searchable(),
                TextColumn::make('status')->badge(),
                TextColumn::make('score')->suffix('%')->placeholder('—'),
                TextColumn::make('eligible')->formatStateUsing(fn ($state): string => is_null($state) ? '—' : ($state ? 'Yes' : 'No'))->color(fn ($state): string => is_null($state) ? 'gray' : ($state ? 'success' : 'danger')),
                TextColumn::make('model_name')->toggleable(),
                TextColumn::make('duration_ms')->label('Duration (ms)')->placeholder('—'),
                TextColumn::make('error_code')->label('Error')->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('is_current')->boolean()->label('Current'),
                TextColumn::make('created_at')->dateTime()->sortable(),
                TextColumn::make('completed_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')->options(ScreeningStatus::class),
                SelectFilter::make('job')->relationship('application.job', 'title')->searchable()->preload(),
                SelectFilter::make('is_current')->options(['1' => 'Current', '0' => 'Not Current']),
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
