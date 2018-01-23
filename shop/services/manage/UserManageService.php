<?php

namespace shop\services\manage;


use shop\entities\user\User;
use shop\forms\manage\user\UserCreateForm;
use shop\forms\manage\user\UserEditForm;
use shop\repositories\UsersRepository;

class UserManageService
{
    private $users;

    public function __construct(UsersRepository $users)
    {
        $this->users = $users;
    }

    public function create(UserCreateForm $form): User
    {
        $user = User::create($form->username, $form->email, $form->password);
        $this->users->save($user);

        return $user;

    }

    public function edit($id, UserEditForm $form): void
    {
        $user = $this->users->get($id);
        $user->edit($form->username, $form->email);
        $this->users->save($user);
    }

    public function remove($id): void
    {
        $user = $this->users->get($id);
        $this->users->remove($user);
    }
}