<?php

namespace frontend\services;


use frontend\forms\SignupForm;
use common\entities\User;

class SignupService
{
    public function __construct()
    {

    }

    public function signup(SignupForm $form): User
    {
        if (User::findByUsername($form->username)) {
            throw new \DomainException('Username is already exists');
        }
        if (User::findByEmail($form->email)) {
            throw new \DomainException('Email is already exists');
        }

        $user = User::create($form->username, $form->email, $form->password);

        if (!$user->save()) {
            throw new \RuntimeException('Saving error');
        }

        return $user;
    }
}