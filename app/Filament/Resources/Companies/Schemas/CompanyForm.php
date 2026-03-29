<?php

namespace App\Filament\Resources\Companies\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CompanyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label(__('Name'))
                    ->required(),
                TextInput::make('slug')
                    ->label(__('Slug'))
                    ->required(),
                FileUpload::make('logo')
                    ->label(__('Logo'))
                    ->disk('public'),
            ]);
    }
}
