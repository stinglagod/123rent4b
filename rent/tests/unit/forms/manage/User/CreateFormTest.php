<?php

namespace rent\tests\unit\forms\manage\User;

use rent\forms\manage\User\UserCreateForm;
use Yii;
use rent\forms\auth\LoginForm;
use common\fixtures\UserFixture;

/**
 * Create form test
 */
class CreateFormTest extends \Codeception\Test\Unit
{
    /**
     * @var \common\tests\UnitTester
     */
    protected $tester;


    public function _before()
    {
        Yii::$app->cache->flush();
        $this->tester->haveFixtures([
            'user' => [
                'class' => UserFixture::class,
                'dataFile' => codecept_data_dir() . 'user.php'
            ]
        ]);
    }

    public function testBlank()
    {
        $model = new UserCreateForm([
            'email' => '',
            'password' => '',
            'name' => '',
            'role' => '',
        ]);

        expect_not($model->validate());
    }


    /**
     *  @dataProvider getVariants
     */
    public function testVariant($email,$password,$name,$role,$clientId,$result)
    {
        $model = new UserCreateForm([
            'email' => $email,
            'password' => $password,
            'name' => $name,
            'role' => $role,
            'default_client_id' => $clientId,
        ]);
        $this->assertEquals($model->validate(),$result);
    }
    public function getVariants()
    {
        return [
            ['test@example.com','password_0','Nikolay','admin',1002,true],
            ['test@example','password_0','Nikolay','admin',1002,false],
            ['','password_0','Nikolay','admin',1002,false],
            ['test@example.com','','Nikolay','admin',1002,false],
            ['test@example.com','password_0','','admin',1002,false],
            ['test@example.com','password_0','Nikolay','',1002,false],
            ['test@example.com','password_0','Nikolay','not_role',1002,false],
            ['test@example.com','password_0','a','admin',1002,false],
            ['nicolas.dianna@hotmail.com','password_0','Nikolay','admin',1002,false],
            ['nicolas.dianna@hotmail.com','password_0','Nikolay','admin','',false],
        ];
    }

}
