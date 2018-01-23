<?php

namespace shop\forms\manage\user;


use shop\entities\user\User;
use yii\base\Model;

class UserEditForm extends Model
{
    public $username;
    public $email;

    public $user;

    public function __construct(User $user, $config = [])
    {
        $this->username = $user->username;
        $this->email = $user->email;
        $this->user = $user;
        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['username', 'email'], 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            [['username', 'email'], 'unique', 'targetClass' => User::class, 'filter' => ['<>', 'id', $this->user->id]],
        ];
    }


}