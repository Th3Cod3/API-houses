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
        echo "UserController get";
    }

    public function list()
    {
        echo "UserController list";
    }

    public function permissions()
    {
        echo "UserController permissions";
    }

}

