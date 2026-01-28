<?php

namespace App\Filament\Resources\CandidateResource\Pages;

use App\Filament\Resources\CandidateResource;
use App\Services\CVTextExtractorService;
use App\Services\CVScoringService;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Log;

class CreateCandidate extends CreateRecord
{
    protected static string $resource = CandidateResource::class;

    protected function afterCreate(): void
    {
        // Score CV if resume was uploaded
        if ($this->record->resume_path) {
            $this->scoreCV($this->record->resume_path);
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


