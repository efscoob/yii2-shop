<?php

namespace frontend\services;


use common\entities\User;
use common\forms\LoginForm;
use common\repositories\UsersRepository;

class AuthService
{
    private $users;

    public function __construct(UsersRepository $users)
    {
        $this->users = $users;
    }

    public function auth(LoginForm $form): User
    {
        $user = $this->users->findByUsernameOrEmail($form->username);
        if (!$user || !$user->isActive() || !$user->validatePassword($form->password)) {
            throw new \DomainException('Login error - Undefined user or wrong password');
        }
        return $user;
    }
}