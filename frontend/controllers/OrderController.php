<?php
/**
 * Created by PhpStorm.
 * User: Алексей
 * Date: 25.10.2019
 * Time: 9:00
 */

namespace frontend\controllers;

use common\models\Order;
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
        $order=Order::getLastActive();
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

}