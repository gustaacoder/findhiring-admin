<?php

namespace App\Filament\Resources\Companies\Pages;

use App\Filament\Resources\Companies\CompaniesResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditCompanies extends EditRecord
{
    protected static string $resource = CompaniesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
