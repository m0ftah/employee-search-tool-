<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Always set type to admin and assign super_admin role
        $data['type'] = 'admin';
        
        return $data;
    }

    protected function afterCreate(): void
    {
        // Assign super_admin role to the created user
        try {
            $this->record->assignRole('super_admin');
        } catch (\Exception $e) {
            // Role might not exist, that's okay
        }
    }
}


