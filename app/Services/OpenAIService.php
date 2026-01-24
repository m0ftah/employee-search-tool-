<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OpenAIService
{
    /**
     * Analyze CV text using OpenAI Chat Completions.
     *
     * @param string $cvText Extracted text from CV
     * @return float|null Score from 0-10, or null if analysis fails
     */
    public function analyzeCV(string $cvText): ?float
    {
        $apiKey = config('openai.api_key');
        $baseUrl = rtrim((string) config('openai.base_url'), '/');
        $path = (string) config('openai.chat_completions_path', '/chat/completions');
        $model = (string) config('openai.model', 'gpt-4o-mini');
        $prompt = (string) config('openai.cv_analysis_prompt');
        $timeout = (int) config('openai.timeout', 30);
        $connectTimeout = (int) config('openai.connect_timeout', 10);

        Log::info('OpenAI CV Analysis Started', [
            'api_key_set' => !empty($apiKey),
            'api_key_length' => strlen($apiKey ?? ''),
            'base_url' => $baseUrl,
            'path' => $path,
            'model' => $model,
            'cv_text_length' => strlen($cvText),
        ]);

        if (empty($apiKey)) {
            Log::error('OpenAI API key is not configured. Please set OPENAI_API_KEY in your .env file.');
            return null;
        }

        if (empty($cvText)) {
            Log::warning('CV text is empty, cannot analyze (OpenAI)');
            return null;
        }

        $url = $baseUrl . '/' . ltrim($path, '/');

        try {
            $payload = [
                'model' => $model,
                'messages' => [
                    ['role' => 'system', 'content' => $prompt],
                    ['role' => 'user', 'content' => $cvText],
                ],
                'temperature' => 0.2,
            ];

            $response = Http::timeout($timeout)
                ->connectTimeout($connectTimeout)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $apiKey,
                ])
                ->post($url, $payload);

            Log::info('OpenAI API Response', [
                'status' => $response->status(),
                'successful' => $response->successful(),
            ]);

            if (!$response->successful()) {
                Log::error('OpenAI API error', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'headers' => $response->headers(),
                ]);
                return null;
            }

            $data = $response->json();
            $text = data_get($data, 'choices.0.message.content');
            $text = is_string($text) ? trim($text) : '';

            Log::info('OpenAI API Response Text', [
                'text' => $text,
                'text_length' => strlen($text),
            ]);

            if (preg_match('/(\d+(?:\.\d+)?)/', $text, $matches)) {
                $score = (float) $matches[1];
                $score = max(0, min(10, $score));

                Log::info('CV Score Calculated (OpenAI)', [
                    'raw_score' => $matches[1],
                    'final_score' => $score,
                ]);

                return $score;
            }

            Log::warning('Could not extract score from OpenAI response', [
                'response_text' => $text,
            ]);
        } catch (Exception $e) {
            Log::error('OpenAI API exception', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
        }

        return null;
    }

    public function isConfigured(): bool
    {
        return !empty(config('openai.api_key'));
    }
}

