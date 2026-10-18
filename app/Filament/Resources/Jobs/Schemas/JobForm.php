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
                Section::make('Job Details')
                    ->description('Define the vacancy details and publication state.')
                    ->schema([
                        TextInput::make('title')
                            ->required()
                            ->maxLength(190)
                            ->columnSpanFull(),
                        TextInput::make('slug')
                            ->required()
                            ->maxLength(190)
                            ->columnSpanFull()
                            ->helperText('The slug is used to construct the public job URL.'),
                        Textarea::make('description')
                            ->required()
                            ->rows(6)
                            ->columnSpanFull(),
                        Select::make('status')
                            ->options(JobStatus::class)
                            ->default(JobStatus::Draft)
                            ->required()
                            ->enum(JobStatus::class)
                            ->native(false),
                        Select::make('created_by')
                            ->relationship('creator', 'name')
                            ->default(auth()->id())
                            ->nullable()
                            ->searchable()
                            ->preload(),
                        DateTimePicker::make('published_at')
                            ->native(false),
                        DateTimePicker::make('closed_at')
                            ->native(false),
                    ])->columns(2),
            ]);
    }
}
