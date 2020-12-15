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
                $house->toArrayWithRooms()
            );
        } else {
            return Response::modelError($house);
        }
    }

    /**
     * retrieve a house with rooms
     *
     * @param Micro $app
     * @param int $id
     * @return array|null
     **/
    public static function editHouse(Micro $app, int $id)
    {
        $house = Houses::findFirst($id);

        if(!self::permissions($house, $app)){
            return;
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
                $house->toArrayWithRooms()
            );
        } else {
            return Response::modelError($house);
        }
    }

    /**
     * retrieve a house with rooms
     *
     * @param Micro $app
     * @param int $id
     * @return array|null
     **/
    public static function getHouse(Micro $app, int $id)
    {
        $house = Houses::findFirst($id);

        if(!$house){
            $app->response->setStatusCode(404, "Not Found")->send();
            return false;
        }

        return Response::responseWrapper(
            "success",
            $house->toArrayWithRooms()
        );
    }

    /**
     * Remove a house and rooms
     *
     * @param Micro $app
     * @param int $id
     * @return array
     **/
    public static function removeHouse(Micro $app, int $id)
    {
        $house = Houses::findFirst($id);
        if(!self::permissions($house, $app)){
            return;
        }

        if($house->delete()){
            return Response::responseWrapper("success", true);
        } else {
            return Response::modelError($house);
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

    /**
     * Verify if a user have permission to manipulate with the object
     *
     * @param Houses $house
     * @param Micro $app
     * @return bool
     **/
    private static function permissions(Houses $house, Micro $app)
    {
        if(!$house){
            $app->response->setStatusCode(404, "Not Found")->send();
            return false;
        }
        if($app->jwt->getUser()->Roles->name != "Admin" && $app->jwt->getUser()->id != $house->user_id){
            $app->response->setStatusCode(403, "Forbidden")->send();
            return false;
        }
        return true;
    }
}