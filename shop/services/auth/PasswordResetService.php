<?php

namespace shop\services\auth;


use shop\repositories\UsersRepository;
use shop\forms\auth\PasswordResetRequestForm;
use shop\forms\auth\ResetPasswordForm;
use shop\entities\user\User;
use Symfony\Component\Yaml\Exception\RuntimeException;
use yii\mail\MailerInterface;

class PasswordResetService
{
    private $mailer;
    private $users;

    public function __construct(UsersRepository $users, MailerInterface $mailer)
    {
        $this->users = $users;
        $this->mailer = $mailer;
    }

    public function validateToken(string $token): void
    {
        if (empty($token) || !is_string($token)) {
            throw new \DomainException('Token error - password reset token cannot be blank');
        }

        if (!$this->users->getByPasswordResetToken($token)) {
            throw new \DomainException('Reset token error');
        }
    }

    public function request(PasswordResetRequestForm $form): void
    {
        $user = $this->users->getByEmail($form->email);
        if (!$user->isActive()) {
            throw new \DomainException('User not active');
        }
        $user->requestPasswordReset();
        $this->users->save($user);
        $sent = $this->mailer
            ->compose(
                ['html' => 'passwordResetToken-html', 'text' => 'passwordResetToken-text'],
                ['user' => $user]
            )
            //->setFrom([\Yii::$app->params['supportEmail'] => $user->username . ' robot'])
            ->setTo($user->email)
            ->setSubject('Reset password for ' . $user->email)
            ->send();
        if (!$sent) {
            throw new RuntimeException('Sending request token mail error');
        }
    }

    public function reset(string $token, ResetPasswordForm $form): void
    {
        $user = $this->users->getByPasswordResetToken($token);
        $user->resetPassword($form->password);
        $this->users->save($user);
    }
}