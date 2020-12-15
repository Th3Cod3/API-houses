<?php

namespace App\Services;

use App\Models\Persons;
use App\Models\Users;
use App\Helpers\Response;
use Phalcon\Mvc\Micro;

class UsersManager
{
    /**
     * Create all the transaction of the creation of an user
     *
     * @param Micro $app
     * @return array
     **/
    public static function createUser(Micro $app)
    {
        $person = new Persons();
        $person->assign($app->request->getPost(), [
            "first_name",
            "last_name",
            "middle_name",
            "birthdate",
            "gender",
        ]);
        
        $user = new Users();
        $user->assign($app->request->getPost(), [
            "username",
            "email"
        ]);

        $user->password = $app->security->hash($app->request->getPost("password"));
        $user->Persons = $person;
        if($user->save()){
            return Response::responseWrapper(
                "success",
                $user->toArrayWithPerson()
            );
        } else {
            return Response::modelError($user);
        }
    }

    /**
     * Verify username and password matches and generate a JWT token
     *
     * @param Micro $app
     * @return array
     **/
    public static function login(Micro $app)
    {
        $user = Users::findFirst([
            "username = :username:",
            "bind" => [
                "username" => $app->request->getPost("username")
            ]
        ]);

        $lastFailMinutesAgo = !$user ?: ((new \DateTime())->getTimestamp() - (new \DateTime($user->last_fail))->getTimestamp()) / 60;

        // verify if user is not locked and password is valid
        if(
            $user
            && ($user->fail_counter < $_ENV["LOCK_FAIL_COUNTER"] || $lastFailMinutesAgo > $_ENV["USER_LOCK_TIME"])
            && $app->request->getPost("password")
            && $app->security->checkHash($app->request->getPost("password"), $user->password)
        ){
            $jti = $app->jwt->jtiGenerator();
            $token = $app->jwt->create($user->id, $jti)->toString();

            $user->last_login = date("Y-m-d H:i:s");
            $user->jti = $jti;
            $user->save();

            return Response::responseWrapper(
                "success",
                ["token" => $token]
            );
        } else if ($user) {
            // Lock user after multiple fail login
            if($lastFailMinutesAgo > $_ENV["USER_LOCK_TIME"]){
                $user->fail_counter = 0;
            }

            if($user->fail_counter < $_ENV["LOCK_FAIL_COUNTER"] || $lastFailMinutesAgo > $_ENV["USER_LOCK_TIME"]) {
                $user->fail_counter++;
                $user->last_fail = date("Y-m-d H:i:s");
                $user->save();
            } else {
                $lockTime = ceil($_ENV['USER_LOCK_TIME'] - $lastFailMinutesAgo);
                return Response::responseWrapper("fail", "This user is locked. Wait $lockTime minutes");
            }
        }

        $app->security->hash(rand());
        return Response::responseWrapper("fail", "Invalid username of password");
    }

    /**
     * Retrieve a user
     *
     * @param Micro $app
     * @param int $id
     * @return array|null
     **/
    public static function getUser(Micro $app, int $id)
    {
        $user = Users::findFirst($id);

        if(!$user){
            $app->response->setStatusCode(404, "Not Found")->send();
            return false;
        }

        return Response::responseWrapper(
            "success",
            $user->toArrayWithPerson()
        );
    }
}