<?php

namespace App\Helpers;

use Phalcon\Http\Response as HttpResponse;

class Response
{
    /**
     * Wrap the response into a JSend specification
     *
     * @param "fail"|"success" $status
     * @param array|string $data
     * @param string $key
     * @return array
     **/
    public static function responseWrapper(string $status, $data, string $key = "data")
    {
        return [
            "status" => $status,
            $key => $data
        ];
    }

    /**
     * Wrap the error messages of a model into the Response::responseWrapper
     *
     * @param array $messages
     * @return array
     **/
    public static function modelError(array $messages)
    {
        $errors = [];
        foreach ($messages as $message) {
            array_push($errors, (string) $message);
        }
        return self::responseWrapper("fail", $errors);
    }

    /**
     * Wrap the success data into the Response::responseWrapper
     *
     * @param mixed $model
     * @return array
     **/
    public static function successResponse($data)
    {
        return self::responseWrapper(
            "success",
            $data
        );
    }

    /**
     * Wrap the error message into the Response::responseWrapper
     *
     * @param mixed $model
     * @return array
     **/
    public static function errorResponse($data)
    {
        return self::responseWrapper(
            "error",
            $data,
            "message"
        );
    }

    /**
     * get
     *
     * @param int $code
     * @param HttpResponse $error
     * @return bool
     **/
    public static function setStatusCode(int $code, HttpResponse $response)
    {
        switch ($code) {
            case 201:
                $response->setStatusCode(201, "Created");
                break;
            case 403:
                $response->setStatusCode(403, "Forbidden");
                break;
            case 404:
                $response->setStatusCode(404, "Not Found");
                break;
            default:
                return false;
        }

        return true;
    }
}
