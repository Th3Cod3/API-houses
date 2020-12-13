<?php
/*
 * this variable comes from the index.php
 * @var $app
 */

use Phalcon\Events\Manager;

use App\Middleware\AuthMiddleware;
use App\Middleware\AclMiddleware;
use App\Middleware\CORSMiddleware;
use App\Middleware\ResponseMiddleware;

/**
 * Create a new Events Manager.
 */
$manager = new Manager();

$manager->attach("micro:beforeHandleRoute", new CORSMiddleware());
$manager->attach("micro:beforeHandleRoute", new AuthMiddleware());
$manager->attach("micro:beforeExecuteRoute", new AclMiddleware());
$manager->attach("micro:afterExecuteRoute", new ResponseMiddleware());

$app->setEventsManager($manager);