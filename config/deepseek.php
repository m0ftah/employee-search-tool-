<?php

return [
    /*
    |--------------------------------------------------------------------------
    | DeepSeek API Configuration
    |--------------------------------------------------------------------------
    |
    | This integration uses an OpenAI-compatible Chat Completions endpoint.
    | You can override the base URL / endpoint / model via environment variables.
    |
    */

    // If you're using OpenRouter, you can set OPENROUTER_API_KEY instead of DEEPSEEK_API_KEY.
    'api_key' => env('DEEPSEEK_API_KEY', env('OPENROUTER_API_KEY', '')),

    // Default base URL for DeepSeek; override if needed.
    // For OpenRouter use: https://openrouter.ai/api/v1
    'base_url' => env('DEEPSEEK_BASE_URL', env('OPENROUTER_BASE_URL', 'https://api.deepseek.com')),

    // OpenAI-compatible chat completions endpoint path.
    'chat_completions_path' => env('DEEPSEEK_CHAT_COMPLETIONS_PATH', '/chat/completions'),

    // Common models:
    // - Direct DeepSeek: deepseek-chat, deepseek-reasoner
    // - OpenRouter: deepseek/deepseek-r1-0528:free (or the model id shown in OpenRouter)
    'model' => env('DEEPSEEK_MODEL', env('OPENROUTER_MODEL', 'deepseek-chat')),

    /*
    |--------------------------------------------------------------------------
    | OpenRouter Optional Headers
    |--------------------------------------------------------------------------
    |
    | OpenRouter recommends sending:
    | - HTTP-Referer: your site URL
    | - X-Title: your app name
    |
    | These are optional but helpful for analytics / attribution.
    |
    */
    'openrouter_referer' => env('OPENROUTER_HTTP_REFERER', env('APP_URL', '')),
    'openrouter_title' => env('OPENROUTER_X_TITLE', env('APP_NAME', '')),

    /*
    |--------------------------------------------------------------------------
    | CV Analysis Prompt
    |--------------------------------------------------------------------------
    */
    'cv_analysis_prompt' => env('DEEPSEEK_CV_PROMPT', 'أنت محلل سير ذاتية محترف. قم بتقييم السيرة الذاتية التالية من 10 نقاط بناءً على الإنجازات، الكلمات المفتاحية ذات الصلة، والتنسيق. يجب أن يكون الناتج هو **رقم واحد فقط** في السطر الأول، ولا شيء سواه. لا تكتب أي تفسير أو مقدمة أو تفاصيل.'),

    /*
    |--------------------------------------------------------------------------
    | Timeout Settings (seconds)
    |--------------------------------------------------------------------------
    */
    // OpenRouter free / reasoning models can be slower; allow a higher default.
    'timeout' => env('DEEPSEEK_TIMEOUT', 90),
    // OpenRouter can occasionally take longer to accept connections (routing / provider selection).
    'connect_timeout' => env('DEEPSEEK_CONNECT_TIMEOUT', 30),

    /*
    |--------------------------------------------------------------------------
    | Output Controls
    |--------------------------------------------------------------------------
    |
    | We only need a single number (0-10), so keep the completion short.
    |
    */
    'max_tokens' => env('DEEPSEEK_MAX_TOKENS', 20),

    /*
    |--------------------------------------------------------------------------
    | Retry Settings
    |--------------------------------------------------------------------------
    */
    'retries' => env('DEEPSEEK_RETRIES', 2),
    'retry_delay_ms' => env('DEEPSEEK_RETRY_DELAY_MS', 500),
];

