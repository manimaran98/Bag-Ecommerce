<?php

/**
 * Resolve the logical entry script from the request URL (clean path, no .php).
 */
function bag_request_slug(): string
{
    $uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
    $scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/index.php'));
    $base = rtrim($scriptDir, '/');
    if ($base !== '' && strpos($uri . '/', $base . '/') === 0) {
        $uri = substr($uri, strlen($base)) ?: '/';
    }
    $path = trim((string) $uri, '/');
    if ($path === '') {
        return 'index';
    }
    if (substr($path, -4) === '.php') {
        $path = substr($path, 0, -4);
    }

    return basename($path);
}

function bag_register_request_entry(): void
{
    $slug = bag_request_slug();
    $map = require BAG_ROOT . '/routes/slugs.php';
    if (!isset($map[$slug])) {
        http_response_code(404);
        header('Content-Type: text/plain; charset=utf-8');
        echo '404 Not Found';

        exit;
    }
    if (!defined('BAG_ENTRY_SCRIPT')) {
        define('BAG_ENTRY_SCRIPT', $map[$slug]);
    }
}
