<?php
namespace backend\controllers;

use backend\models\OrderSearch;
use common\models\Action;
use common\models\Movement;
use common\models\Order;
use common\models\OrderProduct;
use rent\entities\Client\Site;
use rent\entities\Client\Site\MainPage;
use \rent\entities\Shop\Product\Product;
use common\models\Status;
use phpDocumentor\Reflection\DocBlock\Tags\Var_;
use Yii;
use yii\helpers\Json;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use yii\web\BadRequestHttpException;
use common\models\PasswordResetRequestForm;
use common\models\ResetPasswordForm;
//use common\models\SignupForm;
use rent\entities\User\User;
use bitcko\googlecalendar\GoogleCalendarApi;
use yii\helpers\Url;

/**
 * Site controller
 */
class TestController extends Controller
{
    public function actionPhpInfo()
    {
        return phpinfo();
    }
    public function actionIndex()
    {
//        $target=new MainPage();
//        $target->banner1=['fdsf'=>333];
//        var_dump($target);
//        $json=Json::encode($target);
//        var_dump($json);
//        $ob=Json::decode($json);
//        var_dump($ob);
//
//        var_dump(new MainPage($json));
//        $mapper = new \JsonMapper();
//        $ob2 = $mapper->map(Json::decode($json,false), new MainPage());
//        var_dump($ob2);
//        var_dump($ob->banner1);

        $site=Site::findOne(3);
        var_dump($site->mainPage);
        $site->mainPage->mainSlider=[
            'images' => [1,2,3],
            'texts' => ['первый','второй','третий'],
            'urls' => ['/','/catalog','/news']
        ];
        $site->save();



    }

    public function actionBalance()
    {
        $product=Product::findOne(1184);
//        var_dump($product);exit;
        var_dump($product->balance(null,null,true,true));exit;
    }

}
