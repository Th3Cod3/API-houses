<?php
declare(strict_types=1);
namespace App\Controllers;

use App\Services\UsersManager;

class IndexController extends ControllerBase
{

    public function login()
    {
        return UsersManager::login($this->application);

    }

}

