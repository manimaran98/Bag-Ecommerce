<?php

function bag_admin_username(): string
{
    $v = getenv('BAG_ADMIN_USER');

    return ($v !== false && $v !== '') ? $v : 'admin';
}

function bag_is_admin(): bool
{
    return isset($_SESSION['username']) && $_SESSION['username'] === bag_admin_username();
}

function bag_require_login(): void
{
    if (!isset($_SESSION['username'])) {
        $_SESSION['msg'] = 'You must log in first';
        header('Location: ' . bag_url('login'));
        exit;
    }
}

function bag_require_admin(): void
{
    bag_require_login();
    if (!bag_is_admin()) {
        header('HTTP/1.1 403 Forbidden');
        echo 'Access denied.';
        exit;
    }
}

/**
 * Scripts that require an authenticated admin (not customer).
 */
function bag_script_is_admin(string $script): bool
{
    static $adminScripts = [
        'admin.php',
        'users.php',
        'inventory.php',
        'suppliers.php',
        'order.php',
        'purchase.php',
        'addItem.php',
        'addSuppliers.php',
        'newUser.php',
        'editUser.php',
        'editStock.php',
        'editOrder.php',
        'editPurchase.php',
        'editSuppliers.php',
        'viewStock.php',
        'adminEdit.php',
        'deleteOrder.php',
        'deletePurchase.php',
        'deleteStock.php',
        'deleteSuppliers.php',
        'deleteUser.php',
        'adminDelete.php',
    ];

    return in_array($script, $adminScripts, true);
}

/**
 * Scripts that can be viewed without logging in.
 */
function bag_script_is_public(string $script): bool
{
    static $public = [
        'login.php',
        'register.php',
    ];

    return in_array($script, $public, true);
}

function bag_current_entry_script(): string
{
    if (defined('BAG_ENTRY_SCRIPT')) {
        return BAG_ENTRY_SCRIPT;
    }

    return basename($_SERVER['SCRIPT_NAME'] ?? '');
}

function bag_apply_route_guard(): void
{
    $script = bag_current_entry_script();
    if ($script === '') {
        return;
    }
    if (bag_script_is_public($script)) {
        return;
    }
    if (bag_script_is_admin($script)) {
        bag_require_admin();

        return;
    }
    bag_require_login();
}
