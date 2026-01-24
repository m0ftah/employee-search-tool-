<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class CVScoringService
{
    public function analyzeCV(string $cvText): ?float
    {
        $provider = strtolower((string) config('ai.provider', 'gemini'));

        return match ($provider) {
            'openai' => (new OpenAIService())->analyzeCV($cvText),
            'deepseek' => (new DeepSeekService())->analyzeCV($cvText),
            'gemini' => (new GeminiService())->analyzeCV($cvText),
            default => $this->unknownProvider($provider, $cvText),
        };
    }

    private function unknownProvider(string $provider, string $cvText): ?float
    {
        Log::warning('Unknown AI provider for CV scoring', [
            'provider' => $provider,
            'cv_text_length' => strlen($cvText),
        ]);

        // Fallback to Gemini to keep existing behavior.
        return (new GeminiService())->analyzeCV($cvText);
    }
}

