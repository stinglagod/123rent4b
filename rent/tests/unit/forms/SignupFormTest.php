<?php
namespace rent\tests\unit\forms;

use common\fixtures\CategoryFixture;
use common\fixtures\ClientFixture;
use common\fixtures\SiteFixture;
use common\fixtures\UserFixture;
use rent\forms\auth\SignupForm;
use Yii;

class SignupFormTest extends \Codeception\Test\Unit
{
    /**
     * @var \frontend\tests\UnitTester
     */
    protected $tester;

    private $client;
    private $siteRepository;
    private $clientRepository;


    public function _before()
    {
        $this->tester->haveFixtures([
            'user' => [
                'class' => UserFixture::class,
                'dataFile' => codecept_data_dir() . 'user.php'
            ],
            'client' => [
                'class' => ClientFixture::class,
                'dataFile' => codecept_data_dir() . 'client.php'
            ],
        ]);

//        $this->tester->haveFixtures([
//            'client' => [
//                'class' => ClientFixture::class,
//                'dataFile' => codecept_data_dir() . 'client.php'
//            ],
//            'site' => [
//                'class' => SiteFixture::class,
//                'dataFile' => codecept_data_dir() . 'site.php'
//            ],
//            'category' => [
//                'class' => CategoryFixture::class,
//                'dataFile' => codecept_data_dir() . 'category.php'
//            ]
//        ]);
        $this->siteRepository=\Yii::createObject('rent\repositories\Client\SiteRepository');
        $this->clientRepository=\Yii::createObject('rent\repositories\Client\ClientRepository');

        $this->client=$this->clientRepository->get(1000);
    }

    public function testCorrectSignup()
    {

        Yii::$app->settings->initClient($this->client->id);

        $model = new SignupForm([
            'name' => 'some_username',
            'surname' => 'some_username',
            'email' => 'some_email@example.com',
            'password' => 'some_password',
            'password_repeat' => 'some_password',
            'reCaptcha' => 'test'
        ]);
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
