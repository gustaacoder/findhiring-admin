<?php

namespace App\Filament\Resources\Jobs\Pages;

use App\Filament\Resources\JobsResource;
use App\Models\Job;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;

class CreateJob extends CreateRecord
{
    protected static string $resource = JobsResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['slug'] = Str::slug($data['slug'] ?? $data['title']);
        $data['created_by'] = $data['created_by'] ?? auth()->id();

        return $data;
    }

    protected function afterCreate(): void
    {
        if (filled($this->record->slug)) {
            return;
        }

        $this->record->forceFill(['slug' => Str::slug($this->record->title).'-'.$this->record->id])->save();
    }
}
