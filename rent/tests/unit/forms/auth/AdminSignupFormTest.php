<?php
namespace unit\forms\auth;

use Codeception\Test\Unit;
use common\fixtures\ClientFixture;
use common\fixtures\SiteFixture;
use common\fixtures\UserFixture;
use rent\entities\Client\Client;
use rent\entities\Client\Site;
use rent\forms\auth\AdminSignupForm;
use rent\forms\ContactForm;
use rent\repositories\Client\ClientRepository;
use rent\repositories\Client\SiteRepository;
use Yii;

class AdminSignupFormTest extends Unit
{
    protected $tester;
    private ClientRepository $clientRepository;
    private SiteRepository $siteRepository;

    private Site $site;
    private Client $client;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->siteRepository =\Yii::createObject('rent\repositories\Client\SiteRepository');
        $this->clientRepository=\Yii::createObject('rent\repositories\Client\ClientRepository');


    }

    public function _before()
    {


        $this->tester->haveFixtures([
            'client' => [
                'class' => ClientFixture::class,
                'dataFile' => codecept_data_dir() . 'client.php'
            ],
            'site' => [
                'class' => SiteFixture::class,
                'dataFile' => codecept_data_dir() . 'site.php'
            ],
            'user' => [
                'class' => UserFixture::class,
                'dataFile' => codecept_data_dir() . 'user.php'
            ],
        ]);

        $this->client=$this->clientRepository->get(1000);
        $this->site = $this->siteRepository->findByDomainOrId(1000);


    }
    public function testBlank()
    {
        $model = new AdminSignupForm();

        expect_not($model->validate());
    }

    /**
     *  @dataProvider getVariants
     */
    public function testVariant($name,$surname,$clientName,$email,$password,$password_repeat,$result)
    {

        $model = new AdminSignupForm();
        $model->name=$name;
        $model->surname=$surname;
        $model->client->name=$clientName;
        $model->email=$email;
        $model->password=$password;
        $model->password_repeat=$password_repeat;
        $this->assertEquals($model->validate(),$result);

    }
    public function getVariants()
    {

        return [
            'empty' => ['', '', '', '', '', '', false],
            'empty1' => ['Тест', '', '', '', '', '', false],
            'empty2' => ['Тест', 'Тестов', '','', '', '', false],
            'empty3' => ['Тест', 'Тестов', 'Ростелеком','', '', '', false],
            'empty4' => ['Тест', 'Тестов', 'Ростелеком', 'test@example.com', '', '', false],
            'empty5' => ['Тест', 'Тестов', 'Ростелеком', 'test@example.com', '', '', false],
            'empty6' => ['', 'Тестов', 'Ростелеком', 'test@example.com', '12345678', '12345678', false],
            'empty7' => ['Тест', 'Тестов', '', 'test@example.com', '12345678', '12345678', false],
            'empty8' => ['Тест', 'Тестов', 'Ростелеком', '', '12345678', '12345678', false],
            'empty9' => ['Тест', 'Тестов', 'Ростелеком', 'test@example.com', '', '12345678', false],
            'empty10' => ['Тест', 'Тестов', 'Ростелеком', 'test@example.com', '12345678', '', false],

            'notClient' => ['Тест', 'Тестов', '', 'test@example.com', '12345678', '', false],

            'notEmail' => ['Тест', 'Тестов', 'Ростелеком', 'test','12345678', '12345678', false],
            'notEmail2' => ['Тест', 'Тестов', 'Ростелеком', 'test@example', '12345678', '12345678', false],

            'notRepeatPwd' => ['Тест', 'Тестов', 'Ростелеком', 'test@example.com', '12345678', '87654321', false],

            'success' => ['Тест', 'Тестов', 'Ростелеком', 'test@example.com', '12345678', '12345678', true],


        ];
    }
}
