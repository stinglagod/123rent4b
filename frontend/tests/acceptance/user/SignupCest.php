<?php

namespace frontend\tests\acceptance\user;

use frontend\tests\AcceptanceTester;
use common\fixtures\UserFixture;
use rent\entities\User\User;
use yii\helpers\Url;

class SignupCest
{
    public function _before()
    {
        if ($user = User::find()->where(['email' => 'name@example.com'])->one()) {
            $user->delete();
        }
    }

    public function signupSuccessfully(AcceptanceTester $I)
    {
//        $I->amOnPage(Url::to(['/site/login']));
        $I->amOnPage('/site/login');
        $I->wait(1);
        $I->see('Регистрация');

        $I->click('Регистрация');

        $I->wait(1);

        $I->seeInTitle('Войти');

        $I->see('Войти', 'h1');

        $I->fillField('input[name="SignupForm[name]"]', $name='name');
//        $I->fillField('input[name="SignupForm[surname]"]', $surname='surname');
        $I->fillField('input[name="SignupForm[email]"]', $email='name@example.com');
        $I->fillField('input[name="SignupForm[password]"]', 'password_0');
        $I->fillField('input[name="SignupForm[password_repeat]"]', 'password_0');
//        $I->fillField('#signupform-username', 'tester');
//        $I->fillField('#signupform-email', 'tester.email@example.com');
//        $I->fillField('#signupform-password', 'tester_password');
//        $I->fillField('#signupform-verifycode', 'testme');
        $I->wait(5);

        $I->click('#btn_register');

        $I->wait(7);

        $I->see('Проверьте вашу эл.почту для дальнейших инструкций.', '.alert-success');

        $I->wait(7);

        $I->seeRecord('rent\entities\User\User',[
            'name'=>$name,
            'email'=>$email,
        ]);

        $user=$I->grabRecord('rent\entities\User\User',[
            'name'=>$name,
            'email'=>$email,
        ]);
        expect('new user is user?',$user->role==User::DEFAULT_ROLE)->true();
        expect('new user is admin?', $user->role=='admin')->false();
    }

    public function _after()
    {
        if ($user = User::find()->where(['email' => 'name@example.com'])->one()) {
            $user->delete();
        }
    }
}