<?php

namespace App\Middleware;

use Phalcon\Mvc\Micro\MiddlewareInterface;
use Phalcon\Mvc\Micro;

class AuthMiddleware implements MiddlewareInterface
{
    /**
     * Calls the middleware
     *
     * @param Micro $app
     *
     * @returns bool
     */
    public function call(Micro $application)
    {
        return true;
    }
}
