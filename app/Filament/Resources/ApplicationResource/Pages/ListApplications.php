<?php

namespace App\Filament\Resources\ApplicationResource\Pages;

use App\Filament\Resources\ApplicationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListApplications extends ListRecords
{
    protected static string $resource = ApplicationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // No create action - applications are created through job applications
        ];
    }

    protected function getTableQuery(): Builder
    {
        $query = parent::getTableQuery();

        $user = auth()->user();

        // If user is Candidate, only show their applications
        if ($user->isCandidate() && $user->candidate) {
            $query->where('candidate_id', $user->candidate->id);
        }

        // If user is HR, only show applications for their jobs
        if ($user->isHR()) {
            if ($user->hr) {
                $query->whereHas('job', function ($q) use ($user) {
                    $q->where('hr_id', $user->hr->id);
                });
            } else {
                // If HR user doesn't have a profile, show no applications
                $query->whereRaw('1 = 0');
            }
        }

        // Admins can see all applications

        return $query;
    }
}

