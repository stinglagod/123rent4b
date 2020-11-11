<?php
namespace rent\tests\unit\forms;

use common\fixtures\UserFixture;
use rent\forms\auth\SignupForm;

class SignupFormTest extends \Codeception\Test\Unit
{
    /**
     * @var \frontend\tests\UnitTester
     */
    protected $tester;


    public function _before()
    {
        $this->tester->haveFixtures([
            'user' => [
                'class' => UserFixture::class,
                'dataFile' => codecept_data_dir() . 'user.php'
            ]
        ]);
    }

    public function testCorrectSignup()
    {
        $model = new SignupForm([
            'name' => 'some_username',
            'surname' => 'some_username',
            'email' => 'some_email@example.com',
            'password' => 'some_password',
            'password_repeat' => 'some_password',
            'reCaptcha' => 'test'
        ]);
//        echo YII_ENV_PROD;
//var_dump(YII_ENV_PROD);
//var_dump($model->validate());
//var_dump($model->firstErrors);
//exit;
        expect_that($model->validate());
    }

    public function testNotCorrectSignup()
    {
        $model = new SignupForm([
            'name' => 't',
            'email' => 'nicolas.dianna@hotmail.com',
            'password' => 'some_password',
        ]);

        expect_not($model->validate());
        expect_that($model->getErrors('name'));
        expect_that($model->getErrors('email'));

        expect($model->getFirstError('email'))
            ->equals('Email уже используется');
    }
}
