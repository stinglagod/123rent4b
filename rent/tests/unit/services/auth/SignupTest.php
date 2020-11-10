<?php

namespace rent\tests\unit\services\auth;

use Codeception\Test\Unit;
use rent\forms\auth\SignupForm;
use rent\repositories\UserRepository;
use rent\services\auth\SignupService;
use rent\services\RoleManager;
use rent\services\TransactionManager;
use rent\tests\UnitTester;
use yii\mail\MessageInterface;



class SignupTest extends Unit
{
    /** @var SignupService  $service */
    private $service;
    /** @var UnitTester  $tester */
    public $tester;
    /** @var UserRepository $repository */
    public $repository;

    public function _before(): void
    {
        $this->service= new SignupService(
            new UserRepository(),
            \Yii::$app->mailer,
            new TransactionManager(),
            new RoleManager(\Yii::$app->authManager)
        );
        $this->repository=new UserRepository();
    }

    public function testSuccess()
    {
        $form=new SignupForm([
            'name'=>'name',
            'surname'=>'surname',
            'email'=>$email='email@site.com',
            'password'=>'password'
        ]);

        $this->service->signup($form);

        $user=$this->repository->findByUsernameOrEmail($email);


//        // using Yii2 module actions to check email was sent
        $this->tester->seeEmailIsSent();

        /** @var MessageInterface  $emailMessage */
        $emailMessage = $this->tester->grabLastSentEmail();
        expect('valid email is sent', $emailMessage)->isInstanceOf('yii\mail\MessageInterface');
        expect($emailMessage->getTo())->hasKey($email);
        expect($emailMessage->toString())->stringContainsString($user->email_confirm_token);

    }
}