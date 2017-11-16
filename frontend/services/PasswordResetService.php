<?php

namespace frontend\services;


use frontend\forms\PasswordResetRequestForm;
use frontend\forms\ResetPasswordForm;
use common\entities\User;
use Symfony\Component\Yaml\Exception\RuntimeException;
use yii\base\InvalidParamException;

class PasswordResetService
{
    public function validateToken(string $token): void
    {
        if (empty($token) || !is_string($token)) {
            throw new \DomainException('Token error - password reset token cannot be blank');
        }

        if (User::findByPasswordResetToken($token)) {
            throw new \DomainException('Reset token error');
        }
    }

    public function request(PasswordResetRequestForm $form): void
    {
        $user = User::findByEmail($form->email);
        if (!$user->isActive()) {
            throw new \DomainException('User not active');
        }
        $user->requestPasswordReset();
        $user->save();
        $sent = \Yii::$app->mailer
            ->compose(
                ['html' => 'passwordResetToken-html', 'text' => 'passwordResetToken-text'],
                ['user' => $user]
            )
            ->setFrom([\Yii::$app->params['supportEmail'] => $user->username . ' robot'])
            ->setTo($user->email)
            ->setSubject('Reset password for ' . $user->email)
            ->send();
        if (!$sent) {
            throw new RuntimeException('Sending request token mail error');
        }
    }

    public function reset(string $token, ResetPasswordForm $form): void
    {
        $user = User::findByPasswordResetToken($token);
        $user->resetPassword($form->password);
        $user->save();
    }
}