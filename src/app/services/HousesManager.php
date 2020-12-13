<?php

namespace App\Services;

use App\Helpers\Response;
use App\Models\RoomTypes;

class HousesManager
{
    /**
     * Create all the transaction of the creation of an house
     *
     * @return array
     **/
    public static function allRoomType()
    {
        $rooms = RoomTypes::find();
        return Response::responseWrapper(
            "success",
            $rooms->toArray()
        );
    }
}