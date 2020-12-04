<?php

namespace rent\tests\unit\services\auth;

use Codeception\Test\Unit;
use rent\entities\Client\Client;
use rent\entities\User\User;
use rent\forms\auth\SignupForm;
use rent\repositories\UserRepository;
use rent\useCases\auth\SignupService;
use rent\tests\UnitTester;
use yii\mail\MessageInterface;
use Yii;

/**
 * @property \rent\useCases\auth\SignupService $signupService
 * @property UserRepository $userRepository
 * @property UnitTester $tester
 */

class SignupTest extends Unit
{
    private $signupService;
    public $tester;
    public $userRepository;

    public function _before(): void
    {
        $this->signupService=Yii::createObject('rent\useCases\auth\SignupService');
        $this->userRepository=Yii::createObject('rent\repositories\UserRepository');
    }

    public function testSuccess()
    {
        $form=new SignupForm([
            'name'=>'name',
            'surname'=>'surname',
            'email'=>$email='email@site.com',
            'password'=>'password'
        ]);

        $this->signupService->signup($form);

        $user=$this->userRepository->findByUsernameOrEmail($email);


//        // using Yii2 module actions to check email was sent
        $this->tester->seeEmailIsSent();

        /** @var MessageInterface  $emailMessage */
        $emailMessage = $this->tester->grabLastSentEmail();
        expect('valid email is sent', $emailMessage)->isInstanceOf('yii\mail\MessageInterface');
        expect($emailMessage->getTo())->hasKey($email);
        expect($emailMessage->toString())->stringContainsString($user->email_confirm_token);

    }
}