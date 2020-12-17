<?php
declare(strict_types=1);

use App\Seeders\Houses;
use Phalcon\Cli\Console;
use Phalcon\Cli\Dispatcher;
use Phalcon\Di\FactoryDefault;
use Phalcon\Di\FactoryDefault\Cli;

error_reporting(E_ALL);

define('BASE_PATH', __DIR__);
define('APP_PATH', BASE_PATH . '/app');

try {

    require_once BASE_PATH . '/vendor/autoload.php';
    
    /**
     * Read services
     */
    $di = new Cli();
    
    include APP_PATH . '/config/services.php';
    $config = $di->getConfig();
    include APP_PATH . '/config/loader.php';
    
    $dispatcher = new Dispatcher();
    $dispatcher->setDefaultNamespace('App\Tasks');
    $di->setShared('dispatcher', $dispatcher);
    
    $app = new Console($di);

    $arguments = [];
    foreach ($argv as $k => $arg) {
        if ($k === 1) {
            $arguments['task'] = $arg;
        } elseif ($k === 2) {
            $arguments['action'] = $arg;
        } elseif ($k >= 3) {
            $arguments['params'][] = $arg;
        }
    }

    $app->handle($arguments);

} catch (\Exception $e) {
    echo $e->getMessage();
}
