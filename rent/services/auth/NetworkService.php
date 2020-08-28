<?php

namespace rent\services\auth;

use rent\entities\User\User;
use rent\repositories\UserRepository;

class NetworkService
{
    private $users;

    public function __construct(UserRepository $users)
    {
        $this->users = $users;
    }

    public function auth($network, $identity): User
    {
        if ($user = $this->users->findByNetworkIdentity($network, $identity)) {
            return $user;
        }
        $user = User::signupByNetwork($network, $identity);
        $this->users->save($user);
        return $user;
    }
}