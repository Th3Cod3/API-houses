<?php

namespace App\Helpers;

class Response
{
    /**
     * Get the JWT token
     *
     * @param "fail"|"success" $status
     * @param mixed $data
     * @return array
     **/
    public static function responseWrapper($status, $data)
    {
        return [
            "status" => $status,
            "data" => $data
        ];
    }
}