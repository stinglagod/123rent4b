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
use rent\forms\manage\Client\Site\MainPageForm;
use Yii;
use yii\caching\TagDependency;
use yii\helpers\Json;
use yii\log\Logger;
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
        \Yii::warning('test');
//        \Yii::$app->log->logger->log('test',Logger::LEVEL_INFO);
//        TagDependency::invalidate(Yii::$app->cache, ['products']);
    }
    public function actionT2()
    {

    }

    public function actionT3()
    {

    }
    public function actionBalance()
    {

    }

}
