<?php

namespace App\Filament\Resources\ApplicationResource\Pages;

use App\Filament\Resources\ApplicationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditApplication extends EditRecord
{
    protected static string $resource = ApplicationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    public function mount(int | string $record): void
    {
        parent::mount($record);

        $user = auth()->user();

        // HR users cannot edit applications - they can only use Accept/Reject/Hire actions
        if ($user->isHR()) {
            abort(403, 'HR users cannot edit applications. Use Accept, Reject, or Hire actions instead.');
        }

        // If user is Candidate, ensure they can only edit their own applications
        if ($user->isCandidate() && $user->candidate) {
            if ($this->record->candidate_id !== $user->candidate->id) {
                abort(403, 'You can only edit your own applications.');
            }
        }
    }
}

