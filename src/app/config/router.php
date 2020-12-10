<?php
/*
 * this variable comes from the index.php
 * @var $app
 */

use App\Controllers\IndexController;
use App\Controllers\UserController;
use App\Controllers\HouseController;
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

$house = new Collection;

$house->setHandler(new HouseController)
    ->setPrefix('/house')
    ->post('/', 'add')
    ->get('/{id}', 'get')
    ->put('/{id}', 'edit')
    ->delete('/{id}', 'remove')
    ->get('/list', 'list');

$app->mount($house);



