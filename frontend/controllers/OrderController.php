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
use yii\data\ActiveDataProvider;


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
            $data = $this->renderAjax('_modalNewOrder', ['order' => $model]);
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

        $query=$order->getOrderProducts()->andWhere(['<>','product_id',null]);
        $query=$order->getOrderProducts();
        $dataProvider = new ActiveDataProvider([
            'pagination' => [
                'pageSize' => 10,
            ],
            'query' => $query,
        ]);
        return $this->render('cart/cart',[
            'order'=>$order,
            'dataProvider'=>$dataProvider
        ]);
    }

    public function actionCheckout()
    {
        $order=Order::getActual();

        return $this->render('cart/checkout',[
            'order'=>$order,

        ]);
    }
}