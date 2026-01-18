<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiService
{
    /**
     * Analyze CV text using Google Gemini API
     *
     * @param string $cvText Extracted text from CV
     * @return float|null Score from 0-10, or null if analysis fails
     */
    public function analyzeCV(string $cvText): ?float
    {
        $apiKey = config('gemini.api_key');
        $apiUrl = config('gemini.api_url');
        $prompt = config('gemini.cv_analysis_prompt');
        $timeout = config('gemini.timeout');
        $connectTimeout = config('gemini.connect_timeout');

        // Debug logging
        Log::info('Gemini CV Analysis Started', [
            'api_key_set' => !empty($apiKey),
            'api_key_length' => strlen($apiKey ?? ''),
            'api_url' => $apiUrl,
            'cv_text_length' => strlen($cvText),
        ]);

        if (empty($apiKey)) {
            Log::error('Gemini API key is not configured. Please set GEMINI_API_KEY in your .env file.');
            return null;
        }

        if (empty($cvText)) {
            Log::warning('CV text is empty, cannot analyze');
            return null;
        }

        try {
            $requestPayload = [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt],
                            ['text' => $cvText],
                        ],
                    ],
                ],
            ];

            Log::debug('Sending request to Gemini API', [
                'url' => $apiUrl,
                'prompt_length' => strlen($prompt),
                'cv_text_length' => strlen($cvText),
            ]);

            $response = Http::timeout($timeout)
                ->connectTimeout($connectTimeout)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'X-goog-api-key' => $apiKey,
                ])->post($apiUrl, $requestPayload);

            Log::info('Gemini API Response', [
                'status' => $response->status(),
                'successful' => $response->successful(),
            ]);

            if ($response->successful()) {
                $responseData = $response->json();

                Log::debug('Gemini API Response Data', [
                    'has_candidates' => isset($responseData['candidates']),
                    'candidates_count' => isset($responseData['candidates']) ? count($responseData['candidates']) : 0,
                ]);

                // Extract the score from the response
                if (isset($responseData['candidates'][0]['content']['parts'][0]['text'])) {
                    $text = trim($responseData['candidates'][0]['content']['parts'][0]['text']);

                    Log::info('Gemini API Response Text', [
                        'text' => $text,
                        'text_length' => strlen($text),
                    ]);

                    // Extract the first number from the response
                    if (preg_match('/(\d+(?:\.\d+)?)/', $text, $matches)) {
                        $score = (float) $matches[1];

                        // Ensure score is between 0 and 10
                        $score = max(0, min(10, $score));

                        Log::info('CV Score Calculated', [
                            'raw_score' => $matches[1],
                            'final_score' => $score,
                        ]);

                        return $score;
                    } else {
                        Log::warning('Could not extract score from Gemini response', [
                            'response_text' => $text,
                        ]);
                    }
                } else {
                    Log::warning('Unexpected response structure from Gemini API', [
                        'response_keys' => array_keys($responseData ?? []),
                    ]);
                }
            } else {
                $errorBody = $response->body();
                Log::error('Gemini API error', [
                    'status' => $response->status(),
                    'body' => $errorBody,
                    'headers' => $response->headers(),
                ]);
            }
        } catch (Exception $e) {
            Log::error('Gemini API exception', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
        }

        return null;
    }

    /**
     * Check if Gemini API is configured
     *
     * @return bool
     */
    public function isConfigured(): bool
    {
        return !empty(config('gemini.api_key'));
    }
}
