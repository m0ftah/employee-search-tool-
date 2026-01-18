<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Gemini API Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for Google Gemini API integration used for CV analysis.
    |
    */

    'api_key' => env('GEMINI_API_KEY', ''),

    'api_url' => env('GEMINI_API_URL', 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent'),

    'model' => env('GEMINI_MODEL', 'gemini-2.0-flash'),

    /*
    |--------------------------------------------------------------------------
    | CV Analysis Prompt
    |--------------------------------------------------------------------------
    |
    | The prompt used to analyze CVs. You can customize this prompt to change
    | how CVs are evaluated.
    |
    */

    'cv_analysis_prompt' => env('GEMINI_CV_PROMPT', 'أنت محلل سير ذاتية محترف. قم بتقييم السيرة الذاتية التالية من 10 نقاط بناءً على الإنجازات، الكلمات المفتاحية ذات الصلة، والتنسيق. يجب أن يكون الناتج هو **رقم واحد فقط** في السطر الأول، ولا شيء سواه. لا تكتب أي تفسير أو مقدمة أو تفاصيل.'),

    /*
    |--------------------------------------------------------------------------
    | Timeout Settings
    |--------------------------------------------------------------------------
    |
    | Timeout settings for API requests in seconds.
    |
    */

    'timeout' => env('GEMINI_TIMEOUT', 30),

    'connect_timeout' => env('GEMINI_CONNECT_TIMEOUT', 10),
];
