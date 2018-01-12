<?php
/**
 * Created by PhpStorm.
 * User: Johnny
 * Date: 04.01.2018
 * Time: 15:18
 */

namespace shop\services\auth;


use shop\entities\user\User;
use shop\repositories\UsersRepository;

class NetworkService
{
    private $users;

    public function __construct(UsersRepository $users)
    {
        $this->users = $users;
    }

    public function auth($identity, $network): User
    {
        if ($user = $this->users->findByNetworkIdentity($identity, $network)) {
            return $user;
        }
        $user = User::signupByNetwork($identity, $network);
        $this->users->save($user);
        return $user;
    }

    public function attach($id, $identity, $network): void
    {
        if ($this->users->findByNetworkIdentity($identity, $network)) {
            throw new \DomainException('Network is already attached');
        }
        $user = $this->users->get($id);
        $user->attachNetwork($identity, $network);
        $this->users->save($user);
    }
}