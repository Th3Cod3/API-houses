<?php
declare(strict_types=1);
namespace App\Controllers;

class UserController extends ControllerBase
{

    public function add()
    {
        echo "UserController add";
    }

    public function get(int $id)
    {
        echo "UserController get";
    }

    public function list()
    {
        echo "UserController list";
    }

    public function permissions(int $id)
    {
        echo "UserController permissions";
    }

}

