<?php

namespace App\Middleware;

use App\Helpers\Http;
use Phalcon\Events\Event;
use Phalcon\Mvc\Micro\MiddlewareInterface;
use Phalcon\Mvc\Micro;

class AuthMiddleware implements MiddlewareInterface
{
    /** @var array NO_AUTH_NEEDED here comes all the routes that don't require authentication */
    const NO_AUTH_NEEDED = [];

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
        $request = $app->request;
        if(!preg_match("/\/+login\/*/", $request->getQuery("_url"))){
            if($token = Http::trimAuth($request)) {
                if($app->jwt->parse($token)->validate()){
                    return true;
                }
            }
            $app->response->setStatusCode(401, "Unauthorized")->send();
            return false;
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
