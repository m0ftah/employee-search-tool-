<?php

namespace App\Filament\Resources\ApplicationResource\Pages;

use App\Filament\Resources\ApplicationResource;
use Filament\Resources\Pages\CreateRecord;

class CreateApplication extends CreateRecord
{
    protected static string $resource = ApplicationResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // If user is Candidate, automatically assign their candidate ID
        if (auth()->user()->isCandidate() && auth()->user()->candidate) {
            $data['candidate_id'] = auth()->user()->candidate->id;
        }

        return $data;
    }
}

