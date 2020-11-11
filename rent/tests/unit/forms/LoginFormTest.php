<?php

namespace rent\tests\unit\forms;

use Yii;
use rent\forms\auth\LoginForm;
use common\fixtures\UserFixture;

/**
 * Login form test
 */
class LoginFormTest extends \Codeception\Test\Unit
{
    /**
     * @var \common\tests\UnitTester
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

    public function testBlank()
    {
        $model = new LoginForm([
            'email' => '',
            'password' => '',
        ]);

        expect_not($model->validate());
    }

    public function testCorrect()
    {
        $model = new LoginForm([
            'email' => 'test@example.com',
            'password' => 'password_0',
        ]);

        expect_that($model->validate());
    }
    public function testNotCorrect()
    {
        $model = new LoginForm([
            'email' => 'example.com',
            'password' => 'password_0',
        ]);

        expect_not($model->validate());
    }
}
