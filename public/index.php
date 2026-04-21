<?php

/**
 * Single front controller. Set Apache/Nginx document root to this `public/` folder.
 */
define('BAG_ROOT', dirname(__DIR__));

require_once BAG_ROOT . '/routes/HttpKernel.php';

bag_register_request_entry();

require_once BAG_ROOT . '/backend/bootstrap.php';

require_once BAG_ROOT . '/routes/dispatch.php';

bag_dispatch_require_page();
