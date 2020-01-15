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
        $orders=Order::find()->all();
        foreach ($orders as $order) {
            echo $order->id;
            echo '|';
            echo $order->status_id;
            /** @var OrderProduct $orderProduct */
            foreach ($order->orderProducts as $orderProduct) {
                if (empty($orderProduct->status_id)) {
                    $orderProduct->status_id=Status::SMETA;
                    $orderProduct->save();
                }
                $orderProduct->changeStatus();
            }
            $order->changeStatus();
            echo '|';
            echo $order->status_id;
            echo '<br>';
        }
//        $order=Order::findOne(46);

//        return $order->status->name;//        return $order->canChangeStatus(Status::CLOSE);
////        return $order->canChangeStatus(Status::CLOSE)?'':'disabled';
////        return $order->status->name;
//        foreach ($order->orderProducts as $orderProduct) {
//            $orderProduct->changeStatus();
////            echo $orderProduct->isLastCurrentStatus();
//            $order->changeStatus();
////            echo $orderProduct->status_id;
//        }

    }

    public function actionPhpInfo()
    {
        return phpinfo();
    }
}
