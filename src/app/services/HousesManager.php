<?php

namespace App\Services;

use App\Helpers\Response;
use App\Models\Houses;
use App\Models\Rooms;
use App\Models\RoomTypes;
use Phalcon\Mvc\Micro;

class HousesManager
{
    /**
     * Create all the transaction of the creation of an house
     *
     * @param Micro $app
     * @return array
     **/
    public static function createHouse(Micro $app)
    {
        $house = new Houses();
        $house->assign(
            $app->request->getPost(),
            [
                "city",
                "street",
                "number",
                "addition"
            ]
        );

        $house->zip_code = $app->request->getPost("zipCode");
        $house->user_id = $app->jwt->getUser()->id;

        $rooms = [];
        foreach ($app->request->getPost("rooms") ?? [] as $roomPost) {
            $room = new Rooms();
            $room->assign($roomPost, [
                "type_id",
                "width",
                "length",
                "height"
            ]);
            array_push($rooms, $room);
        }

        if($rooms){
            $house->Rooms = $rooms;
        }

        if($house->save()){
            return Response::responseWrapper(
                "success",
                $house->toArray([
                    "id",
                    "city",
                    "street",
                    "zip_code",
                    "number",
                    "addition"
                ])
            );
        } else {
            $errors = [];
            foreach($house->getMessages() as $message){
                array_push($errors, (string) $message);
            }
            return Response::responseWrapper("fail", $errors);
        }
    }

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