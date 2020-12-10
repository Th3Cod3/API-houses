<?php
/*
 * this variable comes from the index.php
 * @var $app
 */

use App\Controllers\IndexController;
use Phalcon\Mvc\Micro\Collection;

$api = new Collection;

$api->setHandler(new IndexController)
    ->get('/login', 'login');


$app->mount($api);



