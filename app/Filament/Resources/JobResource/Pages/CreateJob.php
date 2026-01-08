<?php

namespace App\Filament\Resources\JobResource\Pages;

use App\Filament\Resources\JobResource;
use App\Models\HR;
use Filament\Resources\Pages\CreateRecord;

class CreateJob extends CreateRecord
{
    protected static string $resource = JobResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $user = auth()->user();
        
        // If user is HR, automatically assign their HR ID
        if ($user->isHR()) {
            // Try to get HR record via relationship first
            if (!$user->relationLoaded('hr')) {
                $user->load('hr');
            }
            
            // If relationship doesn't have the record, query directly
            if (!$user->hr) {
                $hr = HR::where('user_id', $user->id)->first();
                if ($hr) {
                    $data['hr_id'] = $hr->id;
                } else {
                    // If HR user doesn't have an HR record, create a minimal one
                    // This ensures HR users can create jobs even if their profile wasn't created properly
                    $hr = HR::create([
                        'user_id' => $user->id,
                        'company_name' => $user->name . "'s Company", // Temporary company name
                    ]);
                    $data['hr_id'] = $hr->id;
                    
                    // Show a notification to complete their profile
                    \Filament\Notifications\Notification::make()
                        ->warning()
                        ->title('HR Profile Created')
                        ->body('A basic HR profile was created for you. Please update your HR profile with complete information.')
                        ->persistent()
                        ->send();
                }
            } else {
                $data['hr_id'] = $user->hr->id;
            }
        }
        
        // Ensure hr_id is set (required field)
        if (!isset($data['hr_id'])) {
            throw new \Exception('HR ID is required. Please select an HR.');
        }

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}

