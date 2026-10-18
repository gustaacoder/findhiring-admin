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
use Filament\Relations\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;

class CriteriaRelationManager extends RelationManager
{
    protected static string $relationship = 'criteria';

    protected static ?string $title = 'Criteria';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(190),
                Textarea::make('description')
                    ->rows(3)
                    ->nullable(),
                Textarea::make('expected_evidence')
                    ->rows(3)
                    ->nullable(),
                Select::make('type')
                    ->options(CriterionType::class)
                    ->default(CriterionType::Required)
                    ->required()
                    ->enum(CriterionType::class)
                    ->native(false),
                TextInput::make('weight')
                    ->numeric()
                    ->required()
                    ->minValue(1)
                    ->maxValue(100)
                    ->helperText('The sum of all criteria weights must be 100% before publication.'),
                TextInput::make('sort_order')
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
                    ->searchable(),
                TextColumn::make('type')
                    ->badge(),
                TextColumn::make('weight')
                    ->label('Weight (%)'),
                TextColumn::make('sort_order')
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
