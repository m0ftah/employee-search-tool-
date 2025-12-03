<?php

namespace App\Filament\Resources\JobResource\Pages;

use App\Filament\Resources\JobResource;
use Filament\Resources\Pages\CreateRecord;

class CreateJob extends CreateRecord
{
    protected static string $resource = JobResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // If user is HR, automatically assign their HR ID
        if (auth()->user()->isHR() && auth()->user()->hr) {
            $data['hr_id'] = auth()->user()->hr->id;
        }

        return $data;
    }
}

