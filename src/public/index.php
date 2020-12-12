<?php
declare(strict_types=1);
use Phalcon\Di\FactoryDefault;
use Phalcon\Mvc\Micro;

error_reporting(E_ALL);

define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');

try {
    /**
     * Include Composer Autoloader
     */
    require_once BASE_PATH . '/vendor/autoload.php';

    $di = new FactoryDefault();

    /**
     * Read services
     */
    include APP_PATH . '/config/services.php';

    /**
     * Get config service for use in inline setup below
     */
    $config = $di->getConfig();

    /**
     * Include Phalcon Autoloader
     */
    include APP_PATH . '/config/loader.php';

    /**
     * Handle the request
     */
    $app = new Micro($di);

    /**
     * Set the routes and middleware
     */
    include APP_PATH . '/config/middleware.php';
    include APP_PATH . '/config/router.php';

    $app->handle($_SERVER['REQUEST_URI']);
} catch (\Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
