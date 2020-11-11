<?php

namespace rent\tests\unit\entities\User;

use Codeception\Test\Unit;
use common\fixtures\UserFixture;
use rent\entities\User\User;

class EditTest extends Unit
{
    /**
     * @var \common\tests\UnitTester
     */
    protected $tester;
    /**
     * @var User
     */
    protected $user;

    public function _before()
    {
        $this->tester->haveFixtures([
            'user' => [
                'class' => UserFixture::class,
                'dataFile' => codecept_data_dir() . 'user.php'
            ]
        ]);
        $this->user=User::findOne(1001);
    }

    public function testSuccess()
    {
        $user=$this->user;
        $user->edit(
            $name = 'New Name',
            $email = 'new-email',
            $surname = 'new-surname',
            $patronymic = 'new-patronymic',
            $telephone = 'new-telephone',
            $default_site = 'new-defaultSite'
        );

        $this->assertEquals($name, $user->name);
        $this->assertEquals($email, $user->email);
        $this->assertEquals($surname, $user->surname);
        $this->assertEquals($patronymic, $user->patronymic);
        $this->assertEquals($telephone, $user->telephone);
        $this->assertEquals($default_site, $user->default_site);
    }
}
