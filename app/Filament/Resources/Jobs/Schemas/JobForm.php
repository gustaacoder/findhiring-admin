<?php

namespace App\Filament\Resources\Jobs\Schemas;

use App\Enums\JobStatus;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class JobForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('Job Details'))
                    ->description(__('Define the vacancy details and publication state.'))
                    ->schema([
                        TextInput::make('title')
                            ->label(__('Title'))
                            ->required()
                            ->maxLength(190)
                            ->columnSpanFull(),
                        TextInput::make('slug')
                            ->label(__('Slug'))
                            ->required()
                            ->maxLength(190)
                            ->columnSpanFull()
                            ->helperText(__('The slug is used to construct the public job URL.')),
                        Textarea::make('description')
                            ->label(__('Description'))
                            ->required()
                            ->rows(6)
                            ->columnSpanFull(),
                        Select::make('status')
                            ->label(__('Status'))
                            ->options(JobStatus::class)
                            ->default(JobStatus::Draft)
                            ->required()
                            ->enum(JobStatus::class)
                            ->native(false),
                        Select::make('created_by')
                            ->label(__('Created By'))
                            ->relationship('creator', 'name')
                            ->default(auth()->id())
                            ->nullable()
                            ->searchable()
                            ->preload(),
                        DateTimePicker::make('published_at')
                            ->label(__('Published At'))
                            ->native(false),
                        DateTimePicker::make('closed_at')
                            ->label(__('Closed At'))
                            ->native(false),
                    ])->columns(2),
            ]);
    }
}
