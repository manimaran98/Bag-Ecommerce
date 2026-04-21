<?php

/**
 * Database settings. Override with environment variables in production.
 */
return [
    'host' => getenv('BAG_DB_HOST') ?: 'localhost',
    'username' => getenv('BAG_DB_USER') ?: 'root',
    'password' => getenv('BAG_DB_PASS') !== false ? (string) getenv('BAG_DB_PASS') : '',
    'database' => getenv('BAG_DB_NAME') ?: 'bag_biz',
];
