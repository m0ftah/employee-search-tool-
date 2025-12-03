<?php

namespace App\Filament\Resources\JobResource\Pages;

use App\Filament\Resources\JobResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditJob extends EditRecord
{
    protected static string $resource = JobResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    public function mount(int | string $record): void
    {
        parent::mount($record);

        // If user is HR, ensure they can only edit their own jobs
        if (auth()->user()->isHR() && auth()->user()->hr) {
            if ($this->record->hr_id !== auth()->user()->hr->id) {
                abort(403, 'You can only edit your own jobs.');
            }
        }
    }
}

