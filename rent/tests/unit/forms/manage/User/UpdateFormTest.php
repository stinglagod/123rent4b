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
    public function testVariant($email,$name,$role,$defaultClientId,$result)
    {
        $model = new UserEditForm($this->user);
        $model->email=$email;
        $model->name=$name;
        $model->role=$role;
        $model->default_client_id=$defaultClientId;

        $this->assertEquals($model->validate(),$result);
    }
    public function getVariants()
    {
        return [
            ['test@example.com','Nikolay','admin',1000,true],
            ['test@example','Nikolay','admin',1000,false],
            ['','Nikolay','admin',1000,false],
            ['test@example.com','','admin',1000,false],
            ['test@example.com','Nikolay','',1000,false],
            ['test@example.com','Nikolay','not_role',1000,false],
            ['test@example.com','a','admin',1000,false],
            ['brady.renner@rutherford.com','Nikolay','admin',1000,false],
            'blankDefaultClient'=>['test@example.com','Nikolay','admin',null,false],
            'noBlankDefaultClient'=>['test@example.com','Nikolay','admin',1000,true],
        ];
    }

}
