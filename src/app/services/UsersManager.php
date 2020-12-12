<?php

namespace App\Services;

use App\Models\Persons;
use App\Models\Users;
use App\Helpers\Response;
use Phalcon\Mvc\Micro;

class UsersManager
{
    /**
     * Create all the transation of the creation of an user
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
                $user->toArray([
                    "id",
                    "username",
                    "email"
                ])
            );
        } else {
            $errors = [];
            foreach($user->getMessages() as $message){
                array_push($errors, (string) $message);
            }
            return Response::responseWrapper("fail", $errors);
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
        if(
            $user
            && $app->request->getPost("password")
            && $app->security->checkHash($app->request->getPost("password"), $user->password)
        ){
            $token = "";
            return Response::responseWrapper(
                "success",
                ["token" => $token]
            );
        } else {
            return Response::responseWrapper("fail", "Invalid username of password");
        }
    }
}