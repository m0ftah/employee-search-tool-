<?php

namespace App\Filament\Resources\ApplicationResource\Pages;

use App\Filament\Resources\ApplicationResource;
use App\Services\CVTextExtractorService;
use App\Services\CVScoringService;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Log;

class CreateApplication extends CreateRecord
{
    protected static string $resource = ApplicationResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // If user is Candidate, automatically assign their candidate ID
        if (auth()->user()->isCandidate() && auth()->user()->candidate) {
            $data['candidate_id'] = auth()->user()->candidate->id;
        }

        return $data;
    }

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
                
                // Update candidate's global profile score as well
                if ($this->record->candidate) {
                    $this->record->candidate->update([
                        'score' => $score,
                        'resume_path' => $resumePath
                    ]);
                }
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

