<?php

namespace App\Middleware;

use Phalcon\Events\Event;
use Phalcon\Mvc\Micro\MiddlewareInterface;
use Phalcon\Mvc\Micro;

class AclMiddleware implements MiddlewareInterface
{

    /**
     * Before anything happens in the controllers
     *
     * @param Event $event
     * @param Micro $app
     *
     * @return bool
     */
    public function beforeExecuteRoute(Event $event, Micro $app) {
        $handlerInfo = $app->getActiveHandler();

        // get the controller and method
        $action = $handlerInfo[1];
        $controllerName = get_class($handlerInfo[0]);
        $controllerName = str_replace('App\\Controllers\\', '', $controllerName);
        $controller = str_replace('Controller', '', $controllerName);

        // Index is public access
        if ($controller === 'Index') {
            return true;
        }

        // Verify if allowed
        $role = $app->jwt->getUser()->Roles->name;
        $allowed = $app->acl->isAllowed($role, $controller, $action);
        if(!$allowed){
            $app->response->setStatusCode(403, "Forbidden")->send();
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
    public function call(Micro $app){
        return true;
    }
}