<?php

namespace shop\repositories;

use shop\entities\user\User;
use yii\base\InvalidParamException;

class UsersRepository
{
    public function findByUsernameOrEmail(string $username, string $email = ''): ?User
    {
        $user = User::findOne(['username' => $username]);
        if (!$user) {
            return User::findOne(['email' => $email]);
        }
        return $user;
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return User|null
     */
    public function getByUsername($username): ?User
    {
        return $this->getBy(['username' => $username]);
    }

    /**
     * Finds user by email
     *
     * @param string $email
     * @return User|null
     */
    public function getByEmail($email): ?User
    {
        return $this->getBy(['email' => $email]);
    }

    public function get($id): ?User
    {
        return $this->getBy(['id' => $id]);
    }

    public function getActive($id): ?User
    {
        return $this->getBy(['id' => $id, 'status' => User::STATUS_ACTIVE]);
    }

    public function getByEmailConfirmToken($token): User
    {
        return $this->getBy(['email_confirm_token' => $token]);
    }

    public function getByPasswordResetToken($token, $status = User::STATUS_ACTIVE): ?User
    {
        if (!User::isPasswordResetTokenValid($token)) {
            throw new InvalidParamException('Wrong password reset token');
        }
        return $this->getBy([
            'password_reset_token' => $token,
            'status' => $status,
        ]);
    }

    public function save(User $user):void
    {
        if (!$user->save()) {
            throw new \RuntimeException();
        }
    }

    private function getBy(array $condition): User
    {
        $user = User::findOne($condition);
        if (!$user) {
            throw new NotFoundException('User not found');
        }

        return $user;
    }
}