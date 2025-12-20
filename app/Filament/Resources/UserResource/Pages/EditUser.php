<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Ensure type remains 'admin' and don't allow it to be changed
        $data['type'] = 'admin';
        
        return $data;
    }

    protected function afterSave(): void
    {
        // Ensure user has super_admin role (in case it was removed somehow)
        if (!$this->record->hasRole('super_admin')) {
            try {
                $this->record->assignRole('super_admin');
            } catch (\Exception $e) {
                // Role might not exist, that's okay
            }
        }
    }
}


