<?php

require_once BAG_ROOT . '/frontend/View.php';

bag_view('pages/register', [
    'pageTitle' => 'Registration',
    'username' => $GLOBALS['username'] ?? '',
    'name' => $GLOBALS['name'] ?? '',
    'contact' => $GLOBALS['contact'] ?? '',
    'address' => $GLOBALS['address'] ?? '',
]);
