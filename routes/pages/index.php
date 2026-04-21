<?php

/**
 * Customer home (runs after backend bootstrap + handlers).
 */
require_once BAG_ROOT . '/frontend/View.php';

if (isset($_GET['logout'])) {
    session_destroy();
    unset($_SESSION['username']);
    header('Location: ' . bag_url('login'));
    exit;
}

bag_view('pages/home', ['pageTitle' => 'Home']);
