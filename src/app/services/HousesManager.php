<?php

namespace App\Services;

use App\Helpers\Response;
use App\Models\Houses;
use App\Models\Permissions;
use App\Models\Rooms;
use App\Models\RoomTypes;
use Phalcon\Mvc\Micro;

class HousesManager
{
    /**
     * Create all the transaction of the creation of a house
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
     * Update a house and rooms
     *
     * @param Micro $app
     * @param int $id
     * @return array
     **/
    public static function editHouse(Micro $app, int $id)
    {
        $house = Houses::findFirst($id);
        if(!$house){
            return $app->response->setStatusCode(404, "Not Found")->send();
        }
        if($app->jwt->getUser()->Roles->name != "Admin" || $app->jwt->getUser()->id != $house->user_id){
            return $app->response->setStatusCode(403, "Forbidden")->send();
        }
        $house->assign(
        $app->request->getPut(),
        [
            "city",
            "street",
            "number",
            "addition"
            ]
        );

        $house->zip_code = $app->request->getPut("zipCode");

        foreach ($app->request->getPut("rooms") ?? [] as $roomPut) {
            $house->Rooms->filter( function($room) use ($roomPut) {
                if($room->id == $roomPut["id"]){
                    return $room->assign($roomPut, [
                        "type_id",
                        "width",
                        "length",
                        "height"
                    ]);
                    $room->save();
                }
            });
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