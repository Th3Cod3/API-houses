<?php
/*
 * this variable comes from the index.php
 * @var $app
 */

use App\Controllers\IndexController;
use App\Controllers\UserController;
use Phalcon\Mvc\Micro\Collection;

$api = new Collection;

$api->setHandler(new IndexController)
    ->get('/login', 'login');

$app->mount($api);

$user = new Collection;

$user->setHandler(new UserController)
    ->setPrefix('/user')
    ->post('/', 'add')
    ->get('/{id}', 'get')
    ->get('/list', 'list')
    ->get('/permissions', 'permissions');

$app->mount($user);



