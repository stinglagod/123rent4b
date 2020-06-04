<?php
namespace backend\controllers;

use backend\models\OrderSearch;
use common\models\Action;
use common\models\Movement;
use common\models\Order;
use common\models\OrderProduct;
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

    public function actionIndex()
    {
        $orders=Order::find()->all();
        /** @var Order $order */
        foreach ($orders as $order) {
            $order->changeStatusPaid();
        }

//        $order=Order::findOne(34);
//
////        return $order->status->name;//        return $order->canChangeStatus(Status::CLOSE);
//        return $order->canChangeStatus(Status::CLOSE)?'':'disabled';
////        return $order->status->name;
//        foreach ($order->orderProducts as $orderProduct) {
//            $orderProduct->changeStatus();
////            echo $orderProduct->isLastCurrentStatus();
//            $order->changeStatus();
////            echo $orderProduct->status_id;
//        }

    }

//    public function actionDeleteOrder($id)
//    {
//        $order=Order::findOne($id);
//        if ($order->delete()) {
//            return 'ok';
//        } else {
//            return $order->firstErrors[0];
//        }
//    }
    public function actionPhpInfo()
    {
        return phpinfo();
    }

    public function actionT()
    {

        print_r(Yii::$app->id);
    }
    public function actionCal()
    {
        $calendarId = 'primary';
        $username="viland73";
        $googleApi = new GoogleCalendarApi($username,$calendarId);
        if($googleApi->checkIfCredentialFileExists()){
            $calendars =    $googleApi->calendarList();
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            \Yii::$app->response->data = $calendars;
        }else{
            return $this->redirect(['auth']);
        }
    }
    public function actionAuth(){

        $redirectUrl = Url::to(['/google-api/auth'],true);
        $calendarId = 'primary';
        $username="viland73";
        $googleApi = new GoogleCalendarApi($username,$calendarId,$redirectUrl);
        if(!$googleApi->checkIfCredentialFileExists()){
            $googleApi->generateGoogleApiAccessToken();
        }
        \Yii::$app->response->data = "Google api authorization done";
    }

    public function actionUrl()
    {
        return $_SERVER['SERVER_NAME'];
    }
}
