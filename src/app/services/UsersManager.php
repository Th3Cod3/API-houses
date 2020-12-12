<?php

namespace App\Services;

use App\Models\Persons;
use App\Models\Users;
use Phalcon\Mvc\Micro;

class UsersManager
{
    /**
     * Create all the transation of the creation of an user
     *
     * @param Micro $app
     * @return bool|array
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
            return true;
        } else {
            $errors = [];
            foreach($user->getMessages() as $message){
                array_push($errors, (string) $message);
            }
            return ["errors" => $errors];
        }
    }
}