<?php

return [
    /*
    |--------------------------------------------------------------------------
    | OpenAI API Configuration
    |--------------------------------------------------------------------------
    |
    | Uses the Chat Completions API.
    |
    */

    'api_key' => env('OPENAI_API_KEY', ''),

    'base_url' => env('OPENAI_BASE_URL', 'https://api.openai.com/v1'),

    'chat_completions_path' => env('OPENAI_CHAT_COMPLETIONS_PATH', '/chat/completions'),

    // Example models: gpt-4o-mini, gpt-4.1-mini (use what your account supports).
    'model' => env('OPENAI_MODEL', 'gpt-4o-mini'),

    'cv_analysis_prompt' => env('OPENAI_CV_PROMPT', 'أنت محلل سير ذاتية محترف. قم بتقييم السيرة الذاتية التالية من 10 نقاط بناءً على الإنجازات، الكلمات المفتاحية ذات الصلة، والتنسيق. يجب أن يكون الناتج هو **رقم واحد فقط** في السطر الأول، ولا شيء سواه. لا تكتب أي تفسير أو مقدمة أو تفاصيل.'),

    'timeout' => env('OPENAI_TIMEOUT', 30),
    'connect_timeout' => env('OPENAI_CONNECT_TIMEOUT', 10),
];

