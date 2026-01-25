<?php

namespace App\Filament\Resources\ApplicationResource\Pages;

use App\Filament\Resources\ApplicationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditApplication extends EditRecord
{
    protected static string $resource = ApplicationResource::class;

    protected ?string $originalStatus = null;

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

        // Store original status before any changes
        $this->originalStatus = $this->record->status;
    }

    protected function beforeSave(): void
    {
        // Refresh the record to get the latest status before save
        $this->record->refresh();
        $this->originalStatus = $this->record->status;
    }

    protected function afterSave(): void
    {
        // Refresh to get the updated status
        $this->record->refresh();
        $newStatus = $this->record->status;

        // Only send notifications if status actually changed to hired or rejected
        if ($this->originalStatus !== $newStatus && $this->record->candidate && $this->record->candidate->user) {
            if ($newStatus === 'hired') {
                // Send accepted notification when status changes to 'hired'
                $this->record->candidate->user->notify(
                    new \App\Notifications\ApplicationAcceptedNotification($this->record)
                );
            } elseif ($newStatus === 'rejected') {
                // Send rejected notification when status changes to 'rejected'
                $this->record->candidate->user->notify(
                    new \App\Notifications\ApplicationRejectedNotification($this->record)
                );
            }
        }
    }
}

