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
        return 'Experiment';
    }

    public static function getPluralLabel(): ?string
    {
        return 'Experiments';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')->required()->maxLength(190),
                TextInput::make('dataset_identifier')->required()->maxLength(190),
                Textarea::make('description')->rows(4)->nullable(),
                Select::make('status')->options(ExperimentStatus::class)->default(ExperimentStatus::Draft)->required()->enum(ExperimentStatus::class)->native(false),
                TextInput::make('model_name')->nullable(),
                TextInput::make('prompt_version')->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable(),
                TextColumn::make('dataset_identifier'),
                TextColumn::make('status')->badge(),
                TextColumn::make('model_name'),
                TextColumn::make('prompt_version'),
                TextColumn::make('runs_count')->counts('runs')->label('Runs'),
                TextColumn::make('started_at')->dateTime()->toggleable(),
                TextColumn::make('completed_at')->dateTime()->toggleable(),
            ])
            ->filters([
                SelectFilter::make('status')->options(ExperimentStatus::class),
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
