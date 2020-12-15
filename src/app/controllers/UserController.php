<?php
declare(strict_types=1);
namespace App\Controllers;

use App\Services\UsersManager;

class UserController extends ControllerBase
{

    public function add()
    {
        return UsersManager::createUser($this->application);
    }

    public function get(int $id)
    {
        return UsersManager::getUser($this->application, $id);
    }

}

