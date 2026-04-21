<?php

/**
 * Phinx configuration. Loads `.env` from the repository root.
 */
$root = __DIR__;
$envFile = $root . '/.env';
if (is_readable($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || (isset($line[0]) && $line[0] === '#')) {
            continue;
        }
        if (strpos($line, '=') === false) {
            continue;
        }
        [$k, $v] = explode('=', $line, 2);
        $k = trim($k);
        $v = trim($v);
        if ($k === '') {
            continue;
        }
        if (getenv($k) === false) {
            putenv($k . '=' . $v);
            $_ENV[$k] = $v;
        }
    }
}

$host = getenv('BAG_DB_HOST') ?: 'localhost';
$user = getenv('BAG_DB_USER') ?: 'root';
$pass = getenv('BAG_DB_PASS') !== false ? (string) getenv('BAG_DB_PASS') : '';
$name = getenv('BAG_DB_NAME') ?: 'bag_biz';

return [
    'paths' => [
        'migrations' => $root . '/database/migrations',
        'seeds' => $root . '/database/seeds',
    ],
    'environments' => [
        'default_migration_table' => 'phinxlog',
        'default_environment' => 'development',
        'development' => [
            'adapter' => 'mysql',
            'host' => $host,
            'name' => $name,
            'user' => $user,
            'pass' => $pass,
            'port' => getenv('BAG_DB_PORT') !== false && getenv('BAG_DB_PORT') !== ''
                ? (string) getenv('BAG_DB_PORT')
                : '3306',
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
        ],
        'production' => [
            'adapter' => 'mysql',
            'host' => $host,
            'name' => $name,
            'user' => $user,
            'pass' => $pass,
            'port' => getenv('BAG_DB_PORT') !== false && getenv('BAG_DB_PORT') !== ''
                ? (string) getenv('BAG_DB_PORT')
                : '3306',
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
        ],
    ],
];
