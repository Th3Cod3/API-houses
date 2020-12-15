<?php
declare(strict_types=1);
namespace App\Controllers;

use App\Services\HousesManager;

class HouseController extends ControllerBase
{

    public function add()
    {
        return HousesManager::createHouse($this->application);
    }

    public function get(int $id)
    {
        echo "HouseController get";
    }

    public function edit(int $id)
    {
        return HousesManager::editHouse($this->application, $id);
    }

    public function remove(int $id)
    {
        return HousesManager::removeHouse($this->application, $id);
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

