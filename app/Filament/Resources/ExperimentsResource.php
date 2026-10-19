<?php

namespace App\Filament\Resources;

use App\Enums\ExperimentStatus;
use App\Filament\Resources\Experiments\Pages\CreateExperiment;
use App\Filament\Resources\Experiments\Pages\EditExperiment;
use App\Filament\Resources\Experiments\Pages\ListExperiments;
use App\Filament\Resources\Experiments\Pages\ViewExperiment;
use App\Models\EvaluationExperiment;
use BackedEnum;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use UnitEnum;

class ExperimentsResource extends Resource
{
    protected static ?string $model = EvaluationExperiment::class;

    protected static BackedEnum|string|null $navigationIcon = Heroicon::Beaker;

    protected static UnitEnum|string|null $navigationGroup = 'Scientific Evaluation';

    public static function getLabel(): ?string
    {
        return __('Experiment');
    }

    public static function getPluralLabel(): ?string
    {
        return __('Experiments');
    }

    public static function getNavigationGroup(): string|UnitEnum|null
    {
        return __('Scientific Evaluation');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')->label(__('Name'))->required()->maxLength(190),
                TextInput::make('dataset_identifier')->label(__('Dataset Identifier'))->required()->maxLength(190),
                Textarea::make('description')->label(__('Description'))->rows(4)->nullable(),
                Select::make('status')->label(__('Status'))->options(ExperimentStatus::class)->default(ExperimentStatus::Draft)->required()->enum(ExperimentStatus::class)->native(false),
                TextInput::make('model_name')->label(__('Model'))->nullable(),
                TextInput::make('prompt_version')->label(__('Prompt Version'))->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label(__('Name'))->searchable(),
                TextColumn::make('dataset_identifier')->label(__('Dataset Identifier')),
                TextColumn::make('status')->label(__('Status'))->badge(),
                TextColumn::make('model_name')->label(__('Model')),
                TextColumn::make('prompt_version')->label(__('Prompt Version')),
                TextColumn::make('runs_count')->counts('runs')->label(__('Runs')),
                TextColumn::make('started_at')->label(__('Started At'))->dateTime()->toggleable(),
                TextColumn::make('completed_at')->label(__('Completed At'))->dateTime()->toggleable(),
            ])
            ->filters([
                SelectFilter::make('status')->label(__('Status'))->options(ExperimentStatus::class),
            ])
            ->recordActions([
                \Filament\Actions\ViewAction::make(),
                \Filament\Actions\EditAction::make(),
            ])
            ->toolbarActions([])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListExperiments::route('/'),
            'create' => CreateExperiment::route('/create'),
            'edit' => EditExperiment::route('/{record}/edit'),
            'view' => ViewExperiment::route('/{record}'),
        ];
    }
}
