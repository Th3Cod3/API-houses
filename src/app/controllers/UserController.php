<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Helpers\Response;
use App\Repositories\UsersRepository;

class UserController extends ControllerBase
{
    /**
     * Handle add user request
     *
     * @return array
     **/
    public function add()
    {
        // generate password hash
        $password = $this->application->security->hash(
            $this->application->request->getPost("password")
        );

        $user = UsersRepository::createUser(
            $this->application->request->getPost(),
            $password
        );

        if ($errors = $user->getMessages()) {
            return Response::modelError($errors);
        } else {
            Response::setStatusCode(201, $this->application->response);
            return Response::successResponse($user->toArray());
        }
    }

    /**
     * Handle get user request
     *
     * @return array
     **/
    public function get(int $user_id)
    {
        $user = UsersRepository::getUser($user_id);

        // if user doesn't exists send 404 status code
        if (!$user) {
            Response::setStatusCode(404, $this->application->response);
            return Response::errorResponse("This user doesn't exists");
        }

        return Response::successResponse($user->toArrayWithPerson());
    }
}
