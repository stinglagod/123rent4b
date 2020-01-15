<?php
namespace backend\controllers;

use common\models\Action;
use common\models\Movement;
use common\models\Order;
use common\models\OrderProduct;
use common\models\Status;
use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use yii\web\BadRequestHttpException;
use common\models\PasswordResetRequestForm;
use common\models\ResetPasswordForm;
//use common\models\SignupForm;
use common\models\User;

/**
 * Site controller
 */
class TestController extends Controller
{

    public function actionIndex()
    {
        $order=Order::findOne(34);
//        return $order->canChangeStatus(Status::CLOSE);
//        return $order->canChangeStatus(Status::CLOSE)?'':'disabled';
//        return $order->status->name;
        foreach ($order->orderProducts as $orderProduct) {
            $orderProduct->changeStatus();
            $order->changeStatus();
            echo $orderProduct->status_id;
        }


    }

    public function actionPhpInfo()
    {
        return phpinfo();
    }
}
