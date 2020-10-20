<?php

namespace rent\tests\unit\entities\User;

use Codeception\Test\Unit;
use rent\entities\User\User;

class SignupTest extends Unit
{
    public function testSuccess()
    {
        $user = User::requestSignup(
            $name = 'name',
            $surname = 'surname',
            $email = 'email@site.com',
            $password = 'password'
        );

        $this->assertEquals($name, $user->name);
        $this->assertEquals($surname, $user->$surname);
        $this->assertEquals($email, $user->email);
        $this->assertNotEmpty($user->password_hash);
        $this->assertNotEquals($password, $user->password_hash);
        $this->assertNotEmpty($user->created_at);
        $this->assertNotEmpty($user->auth_key);
        $this->assertFalse($user->isActive());
        $this->assertTrue($user->isWait());
    }
}