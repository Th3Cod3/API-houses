<?php

namespace App\Repositories;

use App\Models\Houses;
use App\Models\Permissions;
use App\Models\Rooms;
use App\Models\RoomTypes;
use App\Models\Users;

class HousesRepository
{
    const BEDROOM_TYPE_ID = 2;

    const TOILET_TYPE_ID = 3;

    /**
     * Create all the transaction of the creation of a house.
     * If fails make a rollback of the transaction.
     *
     * @param array $post
     * @param int $user_id
     * @return Houses
     **/
    public static function createHouse(array $post, int $user_id)
    {
        $house = new Houses();
        // Assign the data to the model object.
        $house->assign(
            $post,
            $house->getSetFormat()
        );

        // the user can send zipCode instead zip_code
        $house->zip_code = $post["zipCode"] ?? $house->zip_code;

        // Assign the owner/creator.
        $house->user_id = $user_id;

        // Create all the room object to build the transaction.
        $rooms = [];
        foreach ($post["rooms"] ?? [] as $roomPost) {
            $room = new Rooms();
            $room->assign($roomPost, $room->getSetFormat());
            array_push($rooms, $room);
        }

        // if have rooms assign to houses to make a transaction.
        if ($rooms) {
            $house->Rooms = $rooms;
        }

        // make the transaction.
        $house->save();

        return $house;
    }

    /**
     * Update the data of a house and rooms
     *
     * @param array $put
     * @param int $house_id
     * @param Users $user
     * @return House|int
     **/
    public static function editHouse(array $put, int $house_id, Users $user)
    {
        $house = Houses::findFirst($house_id);

        // Verify if have permissions to edit
        $permission = self::permissions($house, $user);
        if ($permission !== true) {
            return $permission;
        }

        // Assign the data to the model object.
        $house->assign(
            $put,
            $house->getSetFormat()
        );

        // the user can send zipCode instead zip_code
        $house->zip_code = $put["zipCode"] ?? $house->zip_code;

        foreach ($put["rooms"] ?? [] as $roomPut) {
            // verify if the passed room_id is valid for this house, otherwise ignored the update
            $house->Rooms->filter(function ($room) use ($roomPut) {
                if ($room->id == $roomPut["id"]) {
                    $room->assign($roomPut, $room->getSetFormat());
                    // update the room info
                    return $room->save();
                }
            });
        }

        // update the house info
        $house->save();

        return $house;
    }

    /**
     * retrieve a house with rooms
     *
     * @param int $id
     * @return House
     **/
    public static function getHouse(int $id)
    {
        return Houses::findFirst($id);
    }

    /**
     * Search for houses with rooms
     *
     * @param array $get
     * @return array|null
     **/
    public static function searchHouse(array $get)
    {
        // Search for houses with different criteria.
        $houses = Houses::query()
            ->innerJoin(Rooms::class, null, "r")
            ->groupBy("r.house_id")
            ->where("CONCAT(street, city, zip_code) LIKE :search:")
            ->having("COUNT(IF(r.type_id = :type_id_bedroom:, 1, NULL)) >= :bedroomsCount: AND COUNT(IF(r.type_id = :type_id_toilet:, 1, NULL)) >= :toiletCount:")
            ->bind([
                "bedroomsCount" => $get["minimalBedroomsCount"] ?? 0,
                "type_id_bedroom" => self::BEDROOM_TYPE_ID,
                "toiletCount" => $get["minimalToiletCount"] ?? 0,
                "type_id_toilet" => self::TOILET_TYPE_ID,
                "search" => "%" . str_replace(" ", "%", trim($get["search"] ?? "")) . "%"
            ])->execute();

        // save all the houses in an array
        $housesArray = [];
        foreach ($houses as $house) {
            array_push($housesArray, $house->toArrayWithRooms());
        }

        return $housesArray;
    }

    /**
     * Remove a house and its rooms
     *
     * @param int $house_id
     * @param Users $user
     * @return bool
     **/
    public static function removeHouse(int $house_id, Users $user)
    {
        $house = Houses::findFirst($house_id);

        // Verify if have permissions to remove
        $permission = self::permissions($house, $user);
        if ($permission !== true) {
            return $permission;
        }

        return $house->delete();
    }

    /**
     * Get all room types
     *
     * @return RoomTypes
     **/
    public static function allRoomType()
    {
        return RoomTypes::find();
    }

    /**
     * Verify if a user have permission to manipulate with the object
     *
     * @param Users $house
     * @param int $user_id
     * @return bool
     * @throws Exception
     **/
    private static function permissions(Houses $house = null, Users $user)
    {
        // verify if house exist
        if (!$house) {
            throw new \Exception("Not Found", 404);
        }

        // Verify if user have permissions
        if ($user->Roles->name != "Admin" && $user->id != $house->user_id) {
            throw new \Exception("Forbidden", 403);
        }

        return true;
    }
}
