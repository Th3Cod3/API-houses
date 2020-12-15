<?php

namespace App\Helpers;

use Phalcon\Mvc\Model;

class Response
{
    /**
     * Wrap the response into a JSend specification
     *
     * @param "fail"|"success" $status
     * @param array|string $data
     * @return array
     **/
    public static function responseWrapper(string $status, $data)
    {
        return [
            "status" => $status,
            "data" => $data
        ];
    }

    /**
     * Wrap the error messages of a model into the Response::responseWrapper
     *
     * @param Model $model
     * @return array
     **/
    public static function modelError(Model $model)
    {
        $errors = [];
        foreach($model->getMessages() as $message){
            array_push($errors, (string) $message);
        }
        return self::responseWrapper("fail", $errors);
    }
}