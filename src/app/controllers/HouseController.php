<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Repositories\HousesRepository;
use App\Helpers\Response;

class HouseController extends ControllerBase
{

    /**
     * Handle add house request
     *
     * @return array
     **/
    public function add()
    {
        $house = HousesRepository::createHouse(
            $this->application->request->getPost(),
            (int) $this->application->jwt->getUser()->id
        );

        if ($errors = $house->getMessages()) {
            return Response::modelError($errors);
        } else {
            Response::setStatusCode(201, $this->application->response);
            return Response::successResponse($house->toArrayWithRooms());
        }
    }

    /**
     * Handle get house request
     *
     * @param int $house_id
     * @return array
     **/
    public function get(int $house_id)
    {
        $house = HousesRepository::getHouse($house_id);

        // if house doesn't exists send 404 status code
        if (!$house) {
            Response::setStatusCode(404, $this->application->response);
            return Response::errorResponse("This house doesn't exists");
        }

        return Response::successResponse($house->toArrayWithRooms());
    }

    /**
     * Handle edit house request
     *
     * @param int $house_id
     * @return array
     **/
    public function edit(int $house_id)
    {
        // if house doesn't exists or user doesn't have permissions throw error
        try {
            $house = HousesRepository::editHouse(
                $this->application->request->getPut(),
                $house_id,
                $this->application->jwt->getUser(),

            );
        } catch (\Throwable $error) {
            return $this->handlePermissionsError($error);
        }

        // check if have errors otherwise send house data
        if ($errors = $house->getMessages()) {
            return Response::modelError($errors);
        } else {
            return Response::successResponse($house->toArrayWithRooms());
        }
    }

    /**
     * Handle remove house request
     *
     * @param int $house_id
     * @return array
     **/
    public function remove(int $house_id)
    {
        // if house doesn't exists or user doesn't have permissions throw error
        try {
            $result = HousesRepository::removeHouse(
                $house_id,
                $this->application->jwt->getUser()
            );
        } catch (\Throwable $error) {
            return $this->handlePermissionsError($error);
        }

        return Response::successResponse($result);
    }

    /**
     * Handle room types request
     *
     * @return array
     **/
    public function types()
    {
        $types = HousesRepository::allRoomType();

        return Response::successResponse($types->toArray());
    }

    /**
     * Handle search request
     *
     * @return array
     **/
    public function search()
    {
        $houses = HousesRepository::searchHouse($this->application->request->get());

        return Response::successResponse($houses);
    }

    /**
     * Set response if error 404 or 403
     *
     * @param Throwable $error
     * @return array
     **/
    private function handlePermissionsError(\Throwable $error)
    {
        // catch error 404 and 403 and send it as response
        $result = Response::setStatusCode($error->getCode(), $this->application->response);

        if (!$result) {
            throw $error;
        }

        return Response::errorResponse("Check if you have permissions or this house exists");
    }
}
