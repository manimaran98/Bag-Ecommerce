<?php

function bag_dispatch_require_page(): void
{
    $entry = BAG_ENTRY_SCRIPT;
    $path = BAG_ROOT . '/routes/pages/' . $entry;
    if (!is_readable($path)) {
        http_response_code(500);
        header('Content-Type: text/plain; charset=utf-8');
        echo 'Application page is missing.';

        exit;
    }
    require $path;
}
