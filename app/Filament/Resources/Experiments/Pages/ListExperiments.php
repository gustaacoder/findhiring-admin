<?php

namespace App\Filament\Resources\Experiments\Pages;

use App\Filament\Resources\ExperimentsResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListExperiments extends ListRecords
{
    protected static string $resource = ExperimentsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
