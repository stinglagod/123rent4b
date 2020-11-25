<?php

namespace rent\tests\unit\forms\manage\Shop;

use common\fixtures\CategoryFixture;
use common\fixtures\ClientFixture;
use common\fixtures\SiteFixture;
use rent\entities\Client\Client;
use rent\entities\Client\Site;
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
 * @property User $user
 * @property UnitTester $tester
 * @property SiteRepository $siteRepository
 */
class CategoryFormTest extends \Codeception\Test\Unit
{

    protected $tester;

    private $site;
    private $siteRepository;


    public function _before()
    {
        $this->siteRepository=\Yii::createObject('rent\repositories\Client\SiteRepository');

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
        $this->site=$this->siteRepository->get(1000);

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
        Yii::$app->params['siteId']=$this->site->id;

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
            'correctDublicateName'=>['Мебель','mebel2',1000,'Описание','Title Taburet',true],
            'notUniqueSlug'=>['Мебель','mebel',1000,'Описание','Title Taburet',false],
            'blankName'=>['','mebel',1000,'Описание','Title Taburet',false],
            'blankSlug'=>['Мебель','',1000,'Описание','Title Taburet',false],
            'blankParentId'=>['Мебель','mebel','','Описание','Title Taburet',false],
            'invalidSlug'=>['Мебель2','0',1000,'Описание','Title Taburet',false],
            'invalidSlug2'=>['Мебель2','111',1000,'Описание','Title Taburet',false],
        ];
    }

}
