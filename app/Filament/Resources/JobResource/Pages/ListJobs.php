<?php

namespace App\Filament\Resources\JobResource\Pages;

use App\Filament\Resources\JobResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListJobs extends ListRecords
{
    protected static string $resource = JobResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->visible(fn () => auth()->user()->isAdmin() || auth()->user()->isHR()),
        ];
    }

    protected function getTableQuery(): Builder
    {
        $query = parent::getTableQuery();

        // If user is HR, only show their jobs
        if (auth()->user()->isHR() && auth()->user()->hr) {
            $query->where('hr_id', auth()->user()->hr->id);
        }

        // If user is Candidate, only show active jobs
        if (auth()->user()->isCandidate()) {
            $query->where('status', 'active');
        }

        // Admins can see all jobs

        return $query;
    }
}

