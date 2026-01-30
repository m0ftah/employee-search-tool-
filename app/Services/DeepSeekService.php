<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DeepSeekService
{
    private const STRICT_SCORE_INSTRUCTION = 'Return ONLY a single number from 0 to 10. No other text. Put the final answer in message.content.';

    private function extractTextFromResponse(mixed $data): string
    {
        // OpenAI/OpenRouter typical path
        $text = data_get($data, 'choices.0.message.content');

        // Some models (e.g., reasoning models) may put output in a different field
        if ((!is_string($text) || trim($text) === '')) {
            $text = data_get($data, 'choices.0.message.reasoning');
        }

        // Legacy / other OpenAI-compatible variants
        if ((!is_string($text) || trim($text) === '')) {
            $text = data_get($data, 'choices.0.text');
        }

        // If content is an array of parts, join them
        if (is_array($text)) {
            $text = implode("\n", array_map(static fn ($v) => is_scalar($v) ? (string) $v : json_encode($v), $text));
        }

        return is_string($text) ? trim($text) : '';
    }

    /**
     * Analyze CV text using DeepSeek (OpenAI-compatible chat completions).
     *
     * @param string $cvText Extracted text from CV
     * @return float|null Score from 0-10, or null if analysis fails
     */
    public function analyzeCV(string $cvText): ?float
    {
        $apiKey = config('deepseek.api_key');
        $baseUrl = rtrim((string) config('deepseek.base_url'), '/');
        $path = (string) config('deepseek.chat_completions_path', '/chat/completions');
        $model = (string) config('deepseek.model', 'deepseek-chat');
        $prompt = (string) config('deepseek.cv_analysis_prompt');
        $timeout = (int) config('deepseek.timeout', 30);
        $connectTimeout = (int) config('deepseek.connect_timeout', 10);
        $maxTokens = (int) config('deepseek.max_tokens', 20);
        $retries = (int) config('deepseek.retries', 2);
        $retryDelayMs = (int) config('deepseek.retry_delay_ms', 500);
        $openRouterReferer = (string) config('deepseek.openrouter_referer', '');
        $openRouterTitle = (string) config('deepseek.openrouter_title', '');

        Log::info('DeepSeek CV Analysis Started', [
            'api_key_set' => !empty($apiKey),
            'api_key_length' => strlen($apiKey ?? ''),
            'base_url' => $baseUrl,
            'path' => $path,
            'model' => $model,
            'cv_text_length' => strlen($cvText),
        ]);

        if (empty($apiKey)) {
            Log::error('DeepSeek API key is not configured. Please set DEEPSEEK_API_KEY in your .env file.');
            return null;
        }

        if (empty($cvText)) {
            Log::warning('CV text is empty, cannot analyze (DeepSeek)');
            return null;
        }

        $url = $baseUrl . '/' . ltrim($path, '/');

        try {
            // Reasoning models like DeepSeek R1 may need more completion tokens to reach the final answer.
            $effectiveMaxTokens = max(1, $maxTokens);
            if (stripos($model, 'r1') !== false) {
                $effectiveMaxTokens = max($effectiveMaxTokens, 256);
            }

            $payload = [
                'model' => $model,
                'messages' => [
                    // Strong, provider-agnostic output constraint
                    ['role' => 'system', 'content' => self::STRICT_SCORE_INSTRUCTION],
                    // Keep the scoring rubric + CV text together as the user content
                    ['role' => 'user', 'content' => $prompt . "\n\nCV:\n" . $cvText . "\n\n" . self::STRICT_SCORE_INSTRUCTION],
                ],
                // We want a single numeric output; keep it deterministic-ish.
                'temperature' => 0,
                // Keep response short; we only need a single number.
                'max_tokens' => $effectiveMaxTokens,
            ];

            Log::debug('Sending request to DeepSeek API', [
                'url' => $url,
                'prompt_length' => strlen($prompt),
                'cv_text_length' => strlen($cvText),
            ]);

            $headers = [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $apiKey,
            ];

            // OpenRouter optional headers (safe to send only when base url is OpenRouter or values are set)
            if ($openRouterReferer !== '') {
                $headers['HTTP-Referer'] = $openRouterReferer;
            }
            if ($openRouterTitle !== '') {
                $headers['X-Title'] = $openRouterTitle;
            }

            $response = Http::timeout($timeout)
                ->connectTimeout($connectTimeout)
                ->retry(max(0, $retries), max(0, $retryDelayMs))
                ->withHeaders($headers)
                ->post($url, $payload);

            Log::info('DeepSeek API Response', [
                'status' => $response->status(),
                'successful' => $response->successful(),
            ]);

            if (!$response->successful()) {
                Log::error('DeepSeek API error', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'headers' => $response->headers(),
                ]);
                return null;
            }

            $data = $response->json();
            $text = $this->extractTextFromResponse($data);
            Log::info('DeepSeek API Response Text', [
                'text' => $text,
                'text_length' => strlen($text),
            ]);

            if (preg_match('/(\d+(?:\.\d+)?)\s*\/\s*10/', $text, $matches)) {
                $score = (float) $matches[1];
            } elseif (preg_match('/(?:score|درجة|تقييم|التقييم|الدرجة)\s*[:=]?\s*(\d+(?:\.\d+)?)/i', $text, $matches)) {
                $score = (float) $matches[1];
            } elseif (preg_match('/^(\d+(?:\.\d+)?)/', $text, $matches)) {
                $score = (float) $matches[1];
            } elseif (preg_match('/(\d+(?:\.\d+)?)/', $text, $matches)) {
                $score = (float) $matches[1];
            }

            if (isset($score)) {
                $score = max(0, min(10, $score));

                Log::info('CV Score Calculated (DeepSeek)', [
                    'raw_text' => $text,
                    'final_score' => $score,
                ]);

                return $score;
            }

            // Log response shape for debugging (helps with OpenRouter model variants)
            Log::warning('DeepSeek response did not include parsable score', [
                'provider_base_url' => $baseUrl,
                'model' => $model,
                'top_level_keys' => is_array($data) ? array_keys($data) : null,
                'choice_0_keys' => is_array(data_get($data, 'choices.0')) ? array_keys(data_get($data, 'choices.0')) : null,
                'message_keys' => is_array(data_get($data, 'choices.0.message')) ? array_keys(data_get($data, 'choices.0.message')) : null,
                // avoid dumping huge content; keep it small
                'raw_snippet' => substr((string) json_encode($data), 0, 1000),
            ]);

            Log::warning('Could not extract score from DeepSeek response', [
                'response_text' => $text,
            ]);
        } catch (Exception $e) {
            Log::error('DeepSeek API exception', [
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
        return !empty(config('deepseek.api_key'));
    }
}

