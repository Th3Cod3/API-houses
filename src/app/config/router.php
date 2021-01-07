<?php
/*
 * this variable comes from the index.php
 * @var $app
 */

use App\Controllers\AuthController;
use App\Controllers\UserController;
use App\Controllers\HouseController;
use Phalcon\Mvc\Micro\Collection;

$api = new Collection;

$api->setHandler(new AuthController)
    ->post('/login', 'login');

$app->mount($api);

$user = new Collection;

$user->setHandler(new UserController)
    ->setPrefix('/user')
    ->post('/', 'add')
    ->get('/{id:[0-9]*}', 'get')
    ->get('/list', 'list')
    ->get('/permissions', 'permissions');

$app->mount($user);

$house = new Collection;

$house->setHandler(new HouseController)
    ->setPrefix('/house')
    ->post('/', 'add')
    ->get('/{id:[0-9]*}', 'get')
    ->put('/{id:[0-9]*}', 'edit')
    ->delete('/{id:[0-9]*}', 'remove')
    ->get('/room_types', 'types')
    ->get('/search', 'search');

$app->mount($house);
