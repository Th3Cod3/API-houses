<?php
declare(strict_types=1);
namespace App\Controllers;

use App\Services\HousesManager;

class HouseController extends ControllerBase
{

    public function add()
    {
        echo "HouseController add";
    }

    public function get(int $id)
    {
        echo "HouseController get";
    }

    public function edit(int $id)
    {
        echo "HouseController edit";
    }

    public function remove(int $id)
    {
        echo "HouseController remove";
    }

    public function types()
    {
        return HousesManager::allRoomType($this->application);
    }

    public function list()
    {
        echo "HouseController list";
    }

}

