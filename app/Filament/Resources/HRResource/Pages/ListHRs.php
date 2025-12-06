<?php

namespace App\Filament\Resources\HRResource\Pages;

use App\Filament\Resources\HRResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListHRs extends ListRecords
{
    protected static string $resource = HRResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}


