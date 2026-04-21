<?php

return [
    'enabled' => env('AI_CHAT_ENABLED', true),

    /*
    | free: Groq + Gemini with failover. paid: Gemini only (no Groq).
    */
    'tier' => env('AI_CHAT_TIER', 'free'),

    'primary_when_free' => strtolower((string) env('AI_CHAT_PRIMARY_WHEN_FREE', 'groq')),

    'fallback_enabled' => filter_var(env('AI_CHAT_FALLBACK_ENABLED', true), FILTER_VALIDATE_BOOL),

    'gemini' => [
        'api_key' => env('GEMINI_API_KEY'),
        'model' => env('GEMINI_MODEL', 'gemini-2.0-flash'),
    ],

    'groq' => [
        'api_key' => env('GROQ_API_KEY'),
        'model' => env('GROQ_MODEL', 'llama-3.1-8b-instant'),
    ],

    'throttle' => [
        'chat_guest_per_minute' => (int) env('AI_CHAT_THROTTLE_GUEST_PER_MINUTE', 20),
        'chat_authenticated_per_minute' => (int) env('AI_CHAT_THROTTLE_AUTH_PER_MINUTE', 60),
        'support_per_minute' => (int) env('AI_SUPPORT_THROTTLE_PER_MINUTE', 5),
    ],

    'captcha' => [
        'driver' => env('AI_CAPTCHA_DRIVER', 'turnstile'),
        'site_key' => env('TURNSTILE_SITE_KEY'),
        'secret_key' => env('TURNSTILE_SECRET_KEY'),
        'recaptcha_site_key' => env('RECAPTCHA_SITE_KEY'),
        'recaptcha_secret_key' => env('RECAPTCHA_SECRET_KEY'),
        'require_for_authenticated' => filter_var(env('AI_CHAT_CAPTCHA_FOR_AUTH', false), FILTER_VALIDATE_BOOL),
    ],
];
