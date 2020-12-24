<?php

namespace App\Helpers;

use Phalcon\Http\Request;

class Http
{
    /**
     * Get the JWT token
     *
     * @param Request $request
     * @return string|false
     **/
    public static function trimAuth(Request $request)
    {
        preg_match("/^Baerer (.*)$/", trim($request->getHeader("Authorization")), $matches);
        return $matches[1] ?? false;
    }
}