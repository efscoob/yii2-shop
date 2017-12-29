<?php

namespace shop\services\auth;


use shop\repositories\UsersRepository;
use shop\forms\auth\SignupForm;
use shop\entities\user\User;
use yii\mail\MailerInterface;

class SignupService
{
    private $mailer;
    private $users;

    public function __construct(UsersRepository $users, MailerInterface $mailer)
    {
        $this->users = $users;
        $this->mailer = $mailer;
    }

    public function signup(SignupForm $form): void
    {
        if ($this->users->findByUsernameOrEmail($form->username, $form->email)) {
            throw new \DomainException('Username or email is already exists');
        }

        $user = User::requestSignup($form->username, $form->email, $form->password);

        $this->users->save($user);

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
        $user = $this->users->getByEmailConfirmToken($token);
        if (!$user) {
            throw new \DomainException('Coudnt find User with token = ' . $token);
        }

        $user->confirmSignup();
        $this->users->save($user);

        return $user;
    }
}