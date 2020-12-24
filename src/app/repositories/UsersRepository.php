<?php

namespace App\Repositories;

use App\Models\Persons;
use App\Models\Users;

class UsersRepository
{
    /**
     * Create all the transaction of the creation of an user.
     * If fails make a rollback of the transaction.
     *
     * @param array $post
     * @param string $password
     * @return Users
     **/
    public static function createUser(array $post, string $password)
    {
        $person = new Persons();
        // Assign the data to the model object.
        $person->assign($post, $person->getSetFormat());

        $user = new Users();
        // Assign the data to the model object.
        $user->assign($post, $user->getSetFormat());

        // set the hash password transaction.
        $user->password = $password;
        $user->Persons = $person;

        // make the transaction.
        $user->save();

        return $user;
    }

    /**
     * Retrieve a user
     *
     * @param int $user_id
     * @return Users
     **/
    public static function getUser(int $user_id)
    {
        return Users::findFirst($user_id);
    }

    /**
     * Retrieve a user by username
     *
     * @param string $username
     * @return Users
     **/
    public static function getUserByUsername(string $username)
    {
        return Users::findFirst([
            "username = :username:",
            "bind" => [
                "username" => $username
            ]
        ]);
    }

    /**
     * Register a successfully login
     *
     * @param Users $user
     * @param string $jti
     **/
    public static function successLogin(Users $user, string $jti)
    {
        $user->last_login = date("Y-m-d H:i:s");
        $user->jti = $jti;
        $user->save();
    }

    /**
     * Register a fail login
     *
     * @param Users $user
     **/
    public static function countFail(Users $user)
    {
        $user->fail_counter++;
        $user->last_fail = date("Y-m-d H:i:s");
        $user->save();
    }

    /**
     * Reset fail counter login
     *
     * @param Users $user
     **/
    public static function resetCounter(Users $user)
    {
        $user->fail_counter = 0;
        $user->save();
    }
}
