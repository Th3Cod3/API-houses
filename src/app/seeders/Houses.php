<?php

namespace App\Seeders;

use App\Models\Houses as HousesModel;
use App\Models\Rooms;
use Faker\Factory;

class Houses
{
    /**
     * Generate fake houses
     *
     * @param bool $save
     * @return HousesModel
     **/
    public static function createHouse(bool $save = false)
    {
        $faker = Factory::create("nl_NL");
        $house = new HousesModel();
        $house->assign([
            "city" => $faker->city,
            "street" => $faker->streetName,
            "number" => rand(1, 150),
            "zip_code" => $faker->postcode,
            "user_id" => rand(1, 2)
        ]);

        $rooms = [];
        for ($i = rand(2, 5); $i > 0; $i--) {
            $room = self::createRoom();
            array_push($rooms, $room);
        }

        $house->Rooms = $rooms;

        if ($save) {
            $house->save();
        }

        return $house;
    }

    /**
     * Generate fake room
     *
     * @param bool $save
     * @return Rooms
     **/
    public static function createRoom(bool $save = false)
    {
        $room = new Rooms();
        $room->assign([
            "type_id" => rand(1, 6),
            "width" => rand(1000, 6000),
            "length" => rand(1000, 6000),
            "height" => rand(2500, 4000)
        ]);

        if ($save) {
            $room->save();
        }

        return $room;
    }
}