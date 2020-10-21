<?php

namespace rent\tests\unit\entities\User;

use Codeception\Test\Unit;
use rent\entities\User\User;

class CreateTest extends Unit
{
    public function testSuccess()
    {
        $user = User::create(
            $name = 'Name',
            $email = 'email',
            $password = 'password'
        );

        $this->assertEquals($name, $user->name);
        $this->assertEquals($email, $user->email);
    }
}
