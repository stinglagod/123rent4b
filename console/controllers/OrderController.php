<?php
namespace console\controllers;

use common\models\Order;
use Yii;
use yii\console\Controller;

class OrderController extends Controller
{
    /**
     * Создание(обновление) событий в google calendar. Начиная с какого года
     * @param int $year
     */
    public function actionToGCalendar($year=2020,$all=true)
    {
        $orders=Order::find()->where(['>','dateBegin',$year.'-01-01 00:00:00' ]);
        if ($all===false) {
            $orders->andWhere(['is','googleEvent_id',new \yii\db\Expression('null')]);
        }
        $orders->orderBy(['id'])->all();
        /** @var Order $order */
        foreach ($orders as $order) {
            echo $order->id;
            echo ' ';
            echo $order->dateBegin;
            echo $order->changeGoogleCalendar()?' ok':' error';
            echo "\n";
        }

    }
}