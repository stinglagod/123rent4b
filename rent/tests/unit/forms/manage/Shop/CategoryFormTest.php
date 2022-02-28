<?php

namespace rent\tests\unit\forms\manage\Shop;

use common\fixtures\CategoryFixture;
use common\fixtures\ClientFixture;
use common\fixtures\SiteFixture;
use rent\entities\Client\Client;
use rent\entities\Client\Site;
use rent\entities\Shop\Category\Category;
use rent\entities\User\User;
use rent\forms\manage\Shop\CategoryForm;
use rent\forms\manage\User\UserCreateForm;
use rent\repositories\Client\SiteRepository;
use rent\tests\UnitTester;
use Yii;
use rent\forms\auth\LoginForm;
use common\fixtures\UserFixture;

/**
 * Create form test
 * @property Site $site
 * @property Client $client
 * @property User $user
 * @property UnitTester $tester
 * @property SiteRepository $siteRepository
 * @property ClientRepository $clientRepository
 */
class CategoryFormTest extends \Codeception\Test\Unit
{

    protected $tester;

    private $site;
    private $client;
    private $siteRepository;
    private $clientRepository;


    public function _before()
    {
        $this->siteRepository=\Yii::createObject('rent\repositories\Client\SiteRepository');
        $this->clientRepository=\Yii::createObject('rent\repositories\Client\ClientRepository');

        $this->tester->haveFixtures([
            'client' => [
                'class' => ClientFixture::class,
                'dataFile' => codecept_data_dir() . 'client.php'
            ],
            'site' => [
                'class' => SiteFixture::class,
                'dataFile' => codecept_data_dir() . 'site.php'
            ],
            'category' => [
                'class' => CategoryFixture::class,
                'dataFile' => codecept_data_dir() . 'category.php'
            ]
        ]);
        $this->client=$this->clientRepository->get(1000);

        $this->site=$this->siteRepository->findByDomainOrId(1000);

        $_SERVER['HTTP_HOST']=$this->site->domain;
        Yii::$app->settings->initClient( $this->client->id);


    }

    public function testBlank()
    {
        $model = new CategoryForm();

        expect_not($model->validate());
    }


    /**
     *  @dataProvider getVariants
     */
    public function testVariant($name,$slug,$parentId,$description,$title,$result)
    {
        $model = new CategoryForm();
        $model->name=$name;
        $model->slug=$slug;
        $model->parentId=$parentId;
        $model->description=$description;
        $model->title=$title;
        $this->assertEquals($model->validate(),$result);

    }
    public function getVariants()
    {
        return [
            'correct'=>['Табурет','taburet',1000,'Описание','Title Taburet',true],
            'correct_'=>['Табурет','_',1000,'Описание','Title Taburet',true],
            'correct_2'=>['Табурет','_2',1000,'Описание','Title Taburet',true],
            'correct_3'=>['Табурет','_d',1000,'Описание','Title Taburet',true],
            'correctDublicateName'=>['Мебель','mebel2',1000,'Описание','Title Taburet',true],
            'notUniqueSlug'=>['Мебель','mebel',1000,'Описание','Title Taburet',false],
            'blankName'=>['','mebel',1000,'Описание','Title Taburet',false],
            'blankSlug'=>['Мебель','',1000,'Описание','Title Taburet',false],
            'blankParentId'=>['Мебель','mebel','','Описание','Title Taburet',false],
            'invalidSlug'=>['Мебель2','0',1000,'Описание','Title Taburet',false],
            'invalidSlug2'=>['Мебель2','111',1000,'Описание','Title Taburet',false],
            'invalidSlug3'=>['Мебель2','.',1000,'Описание','Title Taburet',false],
            'invalidSlug4'=>['Мебель2',',',1000,'Описание','Title Taburet',false],
            'invalidSlug5'=>['Мебель2','"',1000,'Описание','Title Taburet',false],
            'invalidSlug6'=>['Мебель2','|',1000,'Описание','Title Taburet',false],

        ];
    }

}
