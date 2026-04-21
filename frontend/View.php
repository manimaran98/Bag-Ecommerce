<?php

/**
 * Render a PHP template under frontend/views/{path}.php with isolated $data array.
 */
function bag_view(string $path, array $data = []): void
{
    $base = bag_project_root() . '/frontend/views/';
    $rel = str_replace(['..', "\0"], '', $path);
    $file = $base . $rel . '.php';
    if (!is_readable($file)) {
        http_response_code(500);
        echo 'View not found: ' . bag_h($rel);

        exit;
    }
    extract($data, EXTR_SKIP);
    require $file;
}
