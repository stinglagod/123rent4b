<?php

namespace rent\tests\unit\entities\Client;

use Codeception\Test\Unit;
use common\fixtures\UserFixture;
use rent\entities\Client\Client;
use rent\entities\User\User;
use rent\tests\UnitTester;

/**
 * @property Client $client
 * @property User $user
 * @property UnitTester $tester
 */

class ResetPasswordTest extends Unit
{

//    protected $client;
    protected $user;
    protected $tester;


    public function _before()
    {
        $this->tester->haveFixtures([
            'user' => [
                'class' => UserFixture::class,
                'dataFile' => codecept_data_dir() . 'user.php'
            ],
        ]);
//        $this->client=Client::findOne(1001);
        $this->user=User::findOne(1001);
    }

    public function testRequestPasswordResetSuccess()
    {
        $user = $this->getMockBuilder('\rent\entities\User\User')
            ->onlyMethods(['save', 'attributes','generatePasswordResetToken'])
            ->getMock();
        $user->method('save')->willReturn(true);
        $user->method('attributes')->willReturn([
            'password_reset_token',
        ]);
        $user->method('generatePasswordResetToken')->willReturn($randomString='randomString');

        $user->requestPasswordReset();

        $this->assertEquals($user->password_reset_token,$randomString);
    }
    public function testRequestPasswordResetError()
    {
        $user = $this->user;

        $user->password_reset_token=$user->generatePasswordResetToken();

        $this->expectExceptionMessage('Сброс пароля уже запрошен.');

        $user->requestPasswordReset();
    }
    public function testResetPasswordSuccess()
    {
        $user = $this->user;

        $user->requestPasswordReset();

        $user->resetPassword($password='newPassword');

        $this->assertEmpty($user->password_reset_token );
    }
    public function testResetPasswordEmptyTokenError()
    {
        $user = $this->user;

        $this->expectExceptionMessage('Запрос на смену пароля не был отправлен.');

        $user->resetPassword($password='newPassword');
    }
    public function testResetPasswordExpiredTokenError()
    {
        $user = $this->user;

        $user->password_reset_token = \Yii::$app->security->generateRandomString() . '_' . (time()-\Yii::$app->params['user.passwordResetTokenExpire']-1);

        $this->expectExceptionMessage('Запрос на смену пароля истек.');

        $user->resetPassword($password='newPassword');
    }

}
