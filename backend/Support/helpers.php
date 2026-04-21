<?php

/**
 * Repository root (parent of /backend).
 */
function bag_project_root(): string
{
    if (defined('BAG_ROOT')) {
        return BAG_ROOT;
    }

    static $r;

    if ($r === null) {
        $r = dirname(__DIR__, 2);
    }

    return $r;
}

/**
 * HTML escape for output contexts.
 */
function bag_h(?string $s): string
{
    return htmlspecialchars((string) $s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function bag_paths(): array
{
    static $p;

    if ($p === null) {
        $root = bag_project_root();
        $p = [
            'root' => $root,
            'stockImg' => $root . '/public/assets/stockImg',
            'receipt' => $root . '/public/assets/receipt',
        ];
    }

    return $p;
}

/**
 * Store a validated product image upload; returns stored filename or null on failure.
 */
function bag_save_stock_image(array $file): ?string
{
    if (empty($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
        return null;
    }
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    if (!in_array($ext, $allowed, true)) {
        return null;
    }
    $name = bin2hex(random_bytes(8)) . '.' . $ext;
    $dest = bag_paths()['stockImg'] . '/' . $name;
    if (!move_uploaded_file($file['tmp_name'], $dest)) {
        return null;
    }

    return $name;
}

/**
 * Store payment receipt (image or PDF).
 */
function bag_save_receipt_upload(array $file): ?string
{
    if (empty($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
        return null;
    }
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'pdf'];
    if (!in_array($ext, $allowed, true)) {
        return null;
    }
    $name = 'rcpt_' . bin2hex(random_bytes(8)) . '.' . $ext;
    $dest = bag_paths()['receipt'] . '/' . $name;
    if (!move_uploaded_file($file['tmp_name'], $dest)) {
        return null;
    }

    return $name;
}

/**
 * Resolve path under assets/receipt for downloads; returns null if outside directory.
 */
function bag_safe_receipt_path(string $filename): ?string
{
    $base = realpath(bag_paths()['receipt']);
    if ($base === false) {
        return null;
    }
    $clean = basename($filename);
    if ($clean === '' || strpos($clean, '..') !== false) {
        return null;
    }
    $full = realpath($base . '/' . $clean);
    if ($full === false || strncmp($full, $base, strlen($base)) !== 0) {
        return null;
    }

    return $full;
}

/**
 * Base URL path for the front controller (e.g. /myapp/public or empty at domain root).
 */
function bag_url_base(): string
{
    static $b;

    if ($b === null) {
        $b = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/index.php')), '/');
    }

    return $b;
}

/**
 * Public URL path for a route slug (no .php). Optional query array.
 * Example: bag_url('cart') => /public/cart when installed in subdirectory.
 */
function bag_url(string $slug, array $query = []): string
{
    $slug = trim($slug, '/');
    $base = bag_url_base();
    if ($slug === '' || $slug === 'index') {
        $path = $base === '' ? '/' : $base . '/';
    } else {
        $path = $base . '/' . rawurlencode($slug);
    }
    if ($query !== []) {
        $path .= (strpos($path, '?') !== false ? '&' : '?') . http_build_query($query);
    }

    return $path;
}

/**
 * Map routes/pages filename (e.g. users.php) to URL slug for redirects.
 */
function bag_slug_for_entry(string $entry): string
{
    static $inverse;

    if ($inverse === null) {
        $inverse = [];
        $map = require bag_project_root() . '/routes/slugs.php';
        foreach ($map as $slug => $file) {
            $inverse[$file] = $slug;
        }
    }

    return $inverse[$entry] ?? preg_replace('/\.php$/', '', $entry);
}

/**
 * HTTP redirect to another entry script (e.g. users.php).
 */
function bag_redirect_to_entry(string $entry, array $query = []): void
{
    header('Location: ' . bag_url(bag_slug_for_entry($entry), $query));
    exit;
}
