<?php
namespace backend\controllers;

use backend\models\OrderSearch;
use common\models\Action;
use common\models\Movement;
use common\models\Order;
use common\models\OrderProduct;
use \rent\entities\Shop\Product\Product;
use common\models\Status;
use phpDocumentor\Reflection\DocBlock\Tags\Var_;
use Yii;
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
        $movement=\rent\entities\Shop\Product\Movement\Movement::create(time(),null,1000,1184,1,1);
//        $movement=\rent\entities\Shop\Product\Movement\Movement::create(time(),(time()+100001),100,1184,2,1);
//        $movement=\rent\entities\Shop\Product\Movement\Movement::create(time(),null,100,1184,3,1,1);
        return $movement->save();

    }

    public function actionBalance()
    {
        $product=Product::findOne(1184);
//        var_dump($product);exit;
        var_dump($product->balance(null,null,true,true));exit;
    }

}
