<?php

namespace App\Filament\Resources\Jobs\RelationManagers;

use App\Enums\CriterionType;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CriteriaRelationManager extends RelationManager
{
    protected static string $relationship = 'criteria';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('Criteria');
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label(__('Name'))
                    ->required()
                    ->maxLength(190),
                Textarea::make('description')
                    ->label(__('Description'))
                    ->rows(3)
                    ->nullable(),
                Textarea::make('expected_evidence')
                    ->label(__('Expected Evidence'))
                    ->rows(3)
                    ->nullable(),
                Select::make('type')
                    ->label(__('Type'))
                    ->options(CriterionType::class)
                    ->default(CriterionType::Required)
                    ->required()
                    ->enum(CriterionType::class)
                    ->native(false),
                TextInput::make('weight')
                    ->label(__('Weight (%)'))
                    ->numeric()
                    ->required()
                    ->minValue(1)
                    ->maxValue(100)
                    ->helperText(__('The sum of all criteria weights must be 100% before publication.')),
                TextInput::make('sort_order')
                    ->label(__('Sort Order'))
                    ->numeric()
                    ->default(0)
                    ->required(),
            ])->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('Name'))
                    ->searchable(),
                TextColumn::make('type')
                    ->label(__('Type'))
                    ->badge(),
                TextColumn::make('weight')
                    ->label(__('Weight (%)')),
                TextColumn::make('sort_order')
                    ->label(__('Sort Order'))
                    ->sortable(),
            ])
            ->filters([])
            ->headerActions([
                CreateAction::make()
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['sort_order'] = (int) ($data['sort_order'] ?: 0);

                        return $data;
                    }),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()
                    ->before(function ($action, $record) {
                        DB::table('screening_criterion_results')
                            ->where('job_criterion_id', $record->id)
                            ->delete();
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('sort_order');
    }
}
