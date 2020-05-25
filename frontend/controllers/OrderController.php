<?php
/**
 * Created by PhpStorm.
 * User: Алексей
 * Date: 25.10.2019
 * Time: 9:00
 */

namespace frontend\controllers;

use common\models\Order;
use common\models\OrderProduct;
use common\models\Service;
use common\models\Status;
use common\models\User;
use yii\web\Controller;
use yii\filters\VerbFilter;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\helpers\Json;


class OrderController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Обработка добавлиния заказа в корзину
     */
    public function actionAddToBasket()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $order=Order::getActual();
        $post=Yii::$app->request->post();
        $orderBlock_id=isset($post['order_block_id'])?$post['order_block_id']:null;
        $session = Yii::$app->session;
        /** @var $order \common\models\Order */
        if ($order->addToBasket($post['product_id'],$post['qty'],$post['type'],$orderBlock_id)) {

            $session->setFlash('success', 'Товар добавлен в корзину');
            return ['status' => 'success','data'=>''];
        } else {
            $session->setFlash('error', 'Ошибка'. $order->getFirstError('addToBasket'));
            return ['status' => 'false','data'=>$order->getFirstError('addToBasket')];
//            return ['status' => 'false','data'=>$order->getFirstError('addToBasket')];
        }

    }

    /**
     * Создаем или изменяем заказ
     * @param null $id
     * @return array
     */
    public function actionUpdateAjax($id = null)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        /** @var Order $model */
        if (empty($id)) {
            $model = new Order();
        } else {
            $model = $this->findModel($id);
        }

        $session = Yii::$app->session;

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                $session->setFlash('success', 'Заказ сохранен');
                $session['activeOrderId'] = $model->id;
//                $data = $this->renderAjax('_orderHeaderBlock', ['orders' => Order::getActual()]);
                return ['out' => $model, 'status' => 'success', 'data' => ''];
            } else {
                $session->setFlash('error', 'Ошибка при сохранении заказа');
                return ['out' => 'Ошибка при сохранении заказа', 'status' => 'error'];
            }

        }
//        $data=$this->renderAjax('_modalForm',['order'=>$model]);
        if ($model->isNewRecord) {
            $data = $this->renderAjax('_modalUpdateOrderMini', ['order' => $model]);
        } else {
            $data = $this->renderAjax('_modalUpdateOrder', ['order' => $model]);
        }

        return ['status' => 'success', 'data' => $data];
    }

    /**
     * Finds the Order model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Order the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Order::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    public function actionCart()
    {
        /** @var Order $order */
        $order=Order::getActual();

        $query=$order->getOrderProducts()->andWhere(['not', ['product_id' => null]]);
        $dataProvider = new ActiveDataProvider([
            'pagination' => [
                'pageSize' => 10,
            ],
            'query' => $query,
        ]);
//      ищем уже активированную доставку в заказе
        $deliveriesByOrder=$order->getOrderProducts()
            ->andWhere(['type'=>OrderProduct::SERVICE])
            ->joinWith('service')
            ->andWhere(['not',['service.serviceType_id'=>null]])
            ->indexBy('service_id')
            ->all();
        $deliveriesAll=Service::find()->where(['serviceType_id'=>1])->indexBy('id')->all();
//        return print_r($deliveriesByOrder);
//        return print_r($deliveriesAll);
//        return print_r(array_diff_key($deliveriesByOrder,$deliveriesAll));
        $deliveries=array();
        foreach ($deliveriesAll as $item) {
            $deliveries[$item->id]['name']=$item->name;
            $deliveries[$item->id]['cost']=$item->defaultCost;
            $deliveries[$item->id]['checked']='';
            if ($deliveriesByOrder[$item->id]) {
                $deliveries[$item->id]['checked']='checked';
            }
        }
//        return print_r($deliveriesAll);
//        return print_r($deliveriesByOrder);
        return $this->render('cart/cart',[
            'order'=>$order,
            'dataProvider'=>$dataProvider,
            'deliveries'=>$deliveries
        ]);
    }

    public function actionCheckout()
    {
        /** @var Order $order */
        $order=Order::getActual();
        $user=User::findOne(Yii::$app->user->getId());
        if (empty($order->customer)) {
            $order->customer=$user->getShortName();
        }
        if (empty($order->telephone)) {
            $order->telephone=$user->telephone;
        }

        if ($post=Yii::$app->request->post()) {
            if ($order->load($post)) {
                $order->changeStatus(Status::SMETA);
//                return $order->canChangeStatus(Status::SMETA);
                $order->save();
                return $this->render('cart/thank',[
                    'order'=>$order,
                ]);
            }
        }


        return $this->render('cart/checkout',[
            'order'=>$order,
            'user'=>$user

        ]);
    }

    public function actionChangeDelivery($id)
    {
        /** @var Order $order */
        $order=Order::getActual();
        if ($order->addDelivery($id)) {
            $status='success';
            $data=$order->getSumm();
        } else {
            $status='success';
            $data=$order->getErrors('')[0];
        }

        return Json::encode(['status' => $status, 'data' => $data]);
    }

}