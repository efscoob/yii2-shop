<?php

namespace common\tests\unit\entities;

use Codeception\Test\Unit;
use common\entities\User;

class RequestSignupTest extends Unit
{
    public function testSuccess()
    {
        $user = User::requestSignup(
            $username = 'user',
            $email = 'user@example.loc',
            $password = '12345'
        );

        $this->assertEquals($username, $user->username);
        $this->assertEquals($email, $user->email);
        $this->assertNotEquals($password, $user->password_hash);
        $this->assertNotEmpty($user->password_hash);
        $this->assertNotEmpty($user->created_at);
        $this->assertNotEmpty($user->auth_key);
        $this->assertTrue($user->isWait());
        $this->assertFalse($user->isActive());
    }
}
