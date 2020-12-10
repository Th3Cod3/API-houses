<?php
declare(strict_types=1);
namespace App\Controllers;

class IndexController extends ControllerBase
{

    public function login()
    {
        echo "It's me Mario!!";
    }

    public function default()
    {
        echo "404";
    }

}

