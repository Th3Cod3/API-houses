<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Helpers\Response;
use App\Repositories\UsersRepository;

class IndexController extends ControllerBase
{
    /**
     * Verify username and password matches and generate a JWT token
     *
     * @return array
     **/
    public function login()
    {
        $config = $this->application->config;
        $user = UsersRepository::getUserByUsername($this->application->request->getPost("username"));

        $lastFailMinutesAgo = !$user ?: ((new \DateTime())->getTimestamp() - (new \DateTime($user->last_fail ?: ""))->getTimestamp()) / 60;

        // verify if user is not locked and password is valid
        if (
            $user
            && ($user->fail_counter < $config->lock->lockFailCounter || $lastFailMinutesAgo > $config->lock->userLockTime)
            && $this->application->request->getPost("password")
            && $this->application->security->checkHash($this->application->request->getPost("password"), $user->password)
        ) {
            $jti = $this->application->jwt->jtiGenerator();
            $token = $this->application->jwt->create((int) $user->id, $jti)->toString();

            UsersRepository::successLogin($user, $jti);
            return Response::successResponse(["token" => $token]);
        } else if ($user) { // Lock user after multiple fail login

            // reset lock counter
            if ($lastFailMinutesAgo > $config->lock->userLockTime) {
                UsersRepository::resetCounter($user);
            }

            // verify if user is lock otherwise count fail
            if ($user->fail_counter < $config->lock->lockFailCounter || $lastFailMinutesAgo > $config->lock->userLockTime) {
                UsersRepository::countFail($user);
            } else {
                $lockTime = ceil($config->lock->userLockTime - $lastFailMinutesAgo);
                return Response::responseWrapper("fail", "This user is locked. Wait $lockTime minutes");
            }
        }

        $this->application->security->hash((string) rand());
        return Response::responseWrapper("fail", "Invalid username of password");
    }
}
