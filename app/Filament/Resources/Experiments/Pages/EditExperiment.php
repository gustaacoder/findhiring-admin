<?php

namespace App\Filament\Resources\Experiments\Pages;

use App\Filament\Resources\ExperimentsResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditExperiment extends EditRecord
{
    protected static string $resource = ExperimentsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
