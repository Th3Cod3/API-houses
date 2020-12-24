<?php

namespace App\Middleware;

use App\Helpers\Http;
use Phalcon\Events\Event;
use Phalcon\Mvc\Micro\MiddlewareInterface;
use Phalcon\Mvc\Micro;

class AuthMiddleware implements MiddlewareInterface
{
    /**
     * Before anything happens
     *
     * @param Event $event
     * @param Micro $app
     *
     * @return bool
     */
    public function beforeHandleRoute(Event $event, Micro $app)
    {
        // get JWT token from header
        if ($token = Http::trimAuth($app->request)) {
            // parse token
            $app->jwt->parse($token);
        }
    }

    /**
     * Calls the middleware
     *
     * @param Micro $app
     *
     * @return bool
     */
    public function call(Micro $app)
    {
        return true;
    }
}
