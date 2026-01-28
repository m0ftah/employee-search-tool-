<?php

namespace App\Filament\Resources\CandidateResource\Pages;

use App\Filament\Resources\CandidateResource;
use App\Services\CVTextExtractorService;
use App\Services\CVScoringService;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Log;

class EditCandidate extends EditRecord
{
    protected static string $resource = CandidateResource::class;

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
        
        // Store original resume path before any changes
        $this->originalResumePath = $this->record->resume_path;
    }

    protected function afterSave(): void
    {
        // Refresh to get the updated resume path
        $this->record->refresh();
        $newResumePath = $this->record->resume_path;

        // Score CV if:
        // 1. Resume was uploaded or changed, OR
        // 2. Resume exists but candidate doesn't have a score yet
        if ($newResumePath) {
            $shouldScore = false;
            
            // Check if resume path changed
            if ($newResumePath !== $this->originalResumePath) {
                $shouldScore = true;
            }
            // Check if resume exists but no score yet
            elseif ($newResumePath && ($this->record->score === null || $this->record->score === 0)) {
                $shouldScore = true;
            }
            
            if ($shouldScore) {
                $this->scoreCV($newResumePath);
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
            Log::error('CV scoring failed for candidate: ' . $e->getMessage(), [
                'candidate_id' => $this->record->id,
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


