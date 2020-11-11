<?php

namespace rent\tests\unit\forms\manage\User;

use rent\entities\User\User;
use rent\forms\manage\User\UserCreateForm;
use rent\forms\manage\User\UserEditForm;
use Yii;
use rent\forms\auth\LoginForm;
use common\fixtures\UserFixture;

/**
 * Create form test
 */
class UpdateFormTest extends \Codeception\Test\Unit
{
    /**
     * @var \rent\tests\UnitTester
     */
    protected $tester;

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

    public function testBlank()
    {

        $model = new UserEditForm($this->user);
        $model->name='';
        $model->email='';
        $model->role='';

        expect_not($model->validate());
    }


    /**
     *  @dataProvider getVariants
     */
    public function testVariant($email,$name,$role,$result)
    {
        $model = new UserEditForm($this->user);
        $model->email=$email;
        $model->name=$name;
        $model->role=$role;

        $this->assertEquals($model->validate(),$result);
    }
    public function getVariants()
    {
        return [
            ['test@example.com','Nikolay','admin',true],
            ['test@example','Nikolay','admin',false],
            ['','Nikolay','admin',false],
            ['test@example.com','','admin',false],
            ['test@example.com','Nikolay','',false],
            ['test@example.com','Nikolay','not_role',false],
            ['test@example.com','a','admin',false],
            ['brady.renner@rutherford.com','Nikolay','admin',false],
        ];
    }

}
