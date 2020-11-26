<?php

namespace rent\tests\unit\services\auth;

use Codeception\Test\Unit;
use common\fixtures\UserFixture;
use rent\entities\Client\Client;
use rent\entities\User\User;
use rent\forms\auth\LoginForm;
use rent\repositories\UserRepository;
use rent\services\auth\AuthService;
use rent\tests\UnitTester;

/**
 * @property AuthService $authService
 * @property Client $client
 * @property UserRepository $userRepository
 * @property User $userNotActive
 * @property UnitTester $tester
 */

class AuthServiceTest extends Unit
{
    private $authService;
    private $userRepository;

    public $tester;
    public $user;
    public $userNotActive;

    public function _before(): void
    {
        $this->userRepository=\Yii::createObject('rent\repositories\UserRepository');
        $this->authService=\Yii::createObject('rent\services\auth\AuthService');

        $this->tester->haveFixtures([
            'user' => [
                'class' => UserFixture::class,
                'dataFile' => codecept_data_dir() . 'user.php'
            ]
        ]);
        $this->user=$this->userRepository->get(1002);
        $this->userNotActive=$this->userRepository->get(1001);
    }

    public function testAuthSuccess()
    {
        $form=new LoginForm([
            'email'=>   $this->user->email,
            'password'=>$password='12345678'
        ]);

        $user=$this->authService->auth($form);

        $this->assertEquals($this->user->id,$user->id);
    }
    public function testAuthPasswordInvalidError()
    {
        $form=new LoginForm([
            'email'=>   $this->user->email,
            'password'=>$password='passwordInvalid'
        ]);

        $this->expectExceptionMessage('Неверный email или пароль.');

        $this->authService->auth($form);
    }
    public function testAuthUserIsNotActiveError()
    {
        $form=new LoginForm([
            'email'=>   $this->userNotActive->email,
            'password'=>$password='12345678'
        ]);

        $this->expectExceptionMessage('Неверный email или пароль.');

        $this->authService->auth($form);
    }
    public function testAuthUserNotFoundError()
    {
        $form=new LoginForm([
            'email'=>   'notfound@example.com',
            'password'=>$password='12345678'
        ]);

        $this->expectExceptionMessage('Неверный email или пароль.');

        $this->authService->auth($form);
    }
}