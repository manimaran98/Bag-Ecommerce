<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Default Hash Driver
    |--------------------------------------------------------------------------
    |
    | This application uses salted SHA-256 (random 32-byte salt per password).
    |
    */

    'driver' => env('HASH_DRIVER', 'sha256'),

    'bcrypt' => [
        'rounds' => env('BCRYPT_ROUNDS', 12),
        'limit' => null,
    ],

    'argon' => [
        'memory' => 65536,
        'threads' => 1,
        'time' => 4,
        'verify' => true,
    ],

    'argon2id' => [
        'memory' => 65536,
        'threads' => 1,
        'time' => 4,
        'verify' => true,
    ],

];
