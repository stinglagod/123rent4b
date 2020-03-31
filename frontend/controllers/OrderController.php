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
use yii\web\Controller;
use yii\filters\VerbFilter;
use Yii;


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

        if (Yii::$app->request->post('hasEditable')) {
            if ($model = OrderProduct::findOne(Yii::$app->request->post('editableKey'))){
                // fetch the first entry in posted data (there should only be one entry
                // anyway in this array for an editable submission)
                $posted = current($_POST['OrderProduct']);
                $post = ['OrderProduct' => $posted];
                if ($model->load($post)) {
                    $model->save();
                    $output = '';
                }
                $out = ['output' => $output, 'message' => ''];
                return $out;
            }

        }
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
            $data = $this->renderAjax('_modalNewOrder', ['order' => $model]);
        } else {
            $data = $this->renderAjax('_modalUpdateOrder', ['order' => $model]);
        }

        return ['status' => 'success', 'data' => $data];
    }

}