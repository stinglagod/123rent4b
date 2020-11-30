<?php

namespace rent\useCases\auth;

use rent\entities\User\User;
use rent\forms\auth\LoginForm;
use rent\repositories\UserRepository;

class AuthService
{
    private $users;

    public function __construct(UserRepository $users)
    {
        $this->users = $users;
    }

    public function auth(LoginForm $form): User
    {
        $user = $this->users->findByUsernameOrEmail($form->email);
        if (!$user || !$user->isActive() || !$user->validatePassword($form->password)) {
            throw new \DomainException('Неверный email или пароль.');
        }
        return $user;
    }
}