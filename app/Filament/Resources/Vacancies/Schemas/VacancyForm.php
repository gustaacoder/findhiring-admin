<?php

namespace App\Filament\Resources\Vacancies\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class VacancyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('Vacancy Details'))
                    ->description(__('Fundamental informations about the vacancy'))
                    ->icon('heroicon-o-document-text')
                    ->schema([
                        TextInput::make('title')
                            ->label(__('Title'))
                            ->required()
                            ->columnSpanFull(),
                        Textarea::make('description')
                            ->label(__('Description'))
                            ->rows(5)
                            ->required()
                            ->columnSpanFull(),
                        Textarea::make('requirements')
                            ->label(__('Requirements'))
                            ->rows(5)
                            ->required(),
                        Textarea::make('responsibilities')
                            ->label(__('Responsibilities'))
                            ->rows(5)
                            ->required(),
                    ])->columnSpanFull()->columns(2),
                Section::make(__('Vacancy Conditions'))
                        ->schema([
                            TextInput::make('location')
                                ->label(__('Location'))
                                ->prefixIcon('heroicon-o-map-pin'),
                            TextInput::make('salary')
                                ->label(__('Salary'))
                                ->prefix('R$')
                                ->numeric(),
                        ]),

                Section::make(__('Publication Date'))
                    ->schema([
                        DatePicker::make('start_date')
                            ->label(__('Start Date'))
                            ->required()
                            ->prefixIcon('heroicon-o-calendar')
                            ->native(false),
                        DatePicker::make('end_date')
                            ->label(__('Start Date'))
                            ->required()
                            ->prefixIcon('heroicon-o-calendar')
                            ->native(false),
                    ]),
            ]);
    }
}
