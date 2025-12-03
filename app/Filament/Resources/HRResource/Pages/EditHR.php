<?php

namespace App\Filament\Resources\HRResource\Pages;

use App\Filament\Resources\HRResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditHR extends EditRecord
{
    protected static string $resource = HRResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

