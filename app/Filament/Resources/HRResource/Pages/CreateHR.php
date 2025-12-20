<?php

namespace App\Filament\Resources\HRResource\Pages;

use App\Filament\Resources\HRResource;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Hash;

class CreateHR extends CreateRecord
{
    protected static string $resource = HRResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Check if user fields are present
        if (!isset($data['user_name']) || !isset($data['user_email']) || !isset($data['user_password'])) {
            throw new \Exception('User information is required. Please fill in all user fields.');
        }

        // Create the user first
        $user = User::create([
            'name' => $data['user_name'],
            'email' => $data['user_email'],
            'password' => Hash::make($data['user_password']),
            'type' => 'hr',
            'email_verified_at' => now(),
        ]);

        // Assign HR role (try both 'hr' and 'HR' for compatibility)
        try {
            $user->assignRole('hr');
        } catch (\Exception $e) {
            try {
                $user->assignRole('HR');
            } catch (\Exception $e2) {
                // Role might not exist, that's okay
            }
        }

        // Set the user_id for the HR record
        $data['user_id'] = $user->id;

        // Remove user fields from data as they're not part of HR model
        unset($data['user_name'], $data['user_email'], $data['user_password']);

        return $data;
    }
}

