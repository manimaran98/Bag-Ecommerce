<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Admin username (must match users.username row)
    |--------------------------------------------------------------------------
    */
    'admin_username' => env('BAG_ADMIN_USER', 'admin'),

    /*
    |--------------------------------------------------------------------------
    | Public base URL for Stripe redirects (scheme + host [+ port])
    |--------------------------------------------------------------------------
    */
    'public_base_url' => rtrim((string) env('LARAVEL_APP_URL', env('APP_URL', 'http://localhost')), '/'),

    /*
    |--------------------------------------------------------------------------
    | Personalized recommendations (home / product pages)
    |--------------------------------------------------------------------------
    */
    'recommendation_limit' => (int) env('BAG_RECOMMENDATION_LIMIT', 8),
];
