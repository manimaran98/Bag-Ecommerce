<?php

/**
 * Backend bootstrap: environment, session, database, auth, HTTP handlers.
 * Entry scripts require server.php (wrapper) so POST/GET actions run before views.
 */

if (!defined('BAG_ROOT')) {
    define('BAG_ROOT', dirname(__DIR__));
}

$envFile = BAG_ROOT . '/.env';
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

$config = require __DIR__ . '/Config/database.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start([
        'cookie_httponly' => true,
        'cookie_samesite' => 'Lax',
    ]);
}

$db = @mysqli_connect(
    $config['host'],
    $config['username'],
    $config['password'],
    $config['database']
);

if (!$db) {
    die('Database connection failed. Check backend/Config/database.php or BAG_DB_* environment variables.');
}

mysqli_set_charset($db, 'utf8mb4');

$errors = [];

require_once __DIR__ . '/Support/helpers.php';
require_once __DIR__ . '/Support/auth.php';

bag_apply_route_guard();

require_once __DIR__ . '/Http/handlers.php';
