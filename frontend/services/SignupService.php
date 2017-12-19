<?php

namespace frontend\services;


use frontend\forms\SignupForm;
use common\entities\User;
use yii\mail\MailerInterface;

class SignupService
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function signup(SignupForm $form): void
    {
        if (User::findByUsername($form->username)) {
            throw new \DomainException('Username is already exists');
        }
        if (User::findByEmail($form->email)) {
            throw new \DomainException('Email is already exists');
        }

        $user = User::requestSignup($form->username, $form->email, $form->password);

        if (!$user->save()) {
            throw new \RuntimeException('Saving error');
        }

        $send = $this->mailer
            ->compose(
                ['html' => 'auth/signup/confirm-html', 'text' => 'auth/signup/confirm-text'],
                ['user' => $user])
            ->setTo($form->email)
            ->setSubject('Signup information for ' . \Yii::$app->name)
            ->send();

        if (!$send) {
            throw new \DomainException('Sending signup mail error');
        }
    }

    public function confirm($token): User
    {
        if (empty($token)) {
            throw new \DomainException('Token is empty');
        }
        $user = User::findByEmailConfirmToken($token);
        if (!$user) {
            throw new \DomainException('Coudnt find User with token = ' . $token);
        }

        $user->confirmSignup();

        if (!$user->save()) {
            throw new \RuntimeException('Saving error');
        }

        return $user;
    }
}