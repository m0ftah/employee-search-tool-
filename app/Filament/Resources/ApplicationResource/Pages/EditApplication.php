<?php

namespace App\Filament\Resources\ApplicationResource\Pages;

use App\Filament\Resources\ApplicationResource;
use App\Services\CVTextExtractorService;
use App\Services\CVScoringService;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Log;

class EditApplication extends EditRecord
{
    protected static string $resource = ApplicationResource::class;

    protected ?string $originalStatus = null;
    protected ?string $originalResumePath = null;

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

        // Store original status and resume path before any changes
        $this->originalStatus = $this->record->status;
        $this->originalResumePath = $this->record->resume_path;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $user = auth()->user();

        // If candidate, only allow them to update feedback_from_candidate
        if ($user->isCandidate()) {
            // Keep only the feedback field, restore all other fields from the record
            $allowedData = [
                'feedback_from_candidate' => $data['feedback_from_candidate'] ?? null,
            ];
            
            // Merge with existing record data to preserve other fields
            return array_merge($this->record->toArray(), $allowedData);
        }

        return $data;
    }

    protected function beforeSave(): void
    {
        // Refresh the record to get the latest status before save
        $this->record->refresh();
        $this->originalStatus = $this->record->status;
        $this->originalResumePath = $this->record->resume_path;
    }

    protected function afterSave(): void
    {
        // Refresh to get the updated status and resume path
        $this->record->refresh();
        $newStatus = $this->record->status;
        $newResumePath = $this->record->resume_path;

        // Score CV if resume was uploaded or changed
        if ($newResumePath && $newResumePath !== $this->originalResumePath) {
            $this->scoreCV($newResumePath);
        }

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

    protected function scoreCV(string $resumePath): void
    {
        try {
            $extractor = new CVTextExtractorService();
            $cvText = $extractor->extractText($resumePath);

            $scoringService = new CVScoringService();
            $score = $scoringService->analyzeCV($cvText);

            if ($score !== null) {
                $this->record->update(['score' => $score]);
                
                \Filament\Notifications\Notification::make()
                    ->success()
                    ->title(__('app.cv_scored'))
                    ->body(__('app.cv_scored_success', ['score' => (int)$score]))
                    ->send();
            }
        } catch (\Exception $e) {
            Log::error('CV scoring failed for application: ' . $e->getMessage(), [
                'application_id' => $this->record->id,
                'resume_path' => $resumePath,
                'exception' => $e,
            ]);

            \Filament\Notifications\Notification::make()
                ->warning()
                ->title(__('app.cv_scoring_failed'))
                ->body(__('app.cv_scoring_failed_message'))
                ->send();
        }
    }
}

