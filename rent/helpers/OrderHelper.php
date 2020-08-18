<?php

namespace rent\helpers;

use rent\entities\Shop\Order\Order;
use rent\entities\Shop\Order\Status;
use rent\entities\Shop\Product\Product;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class OrderHelper
{
    public static function statusList(): array
    {
        return [
            Status::NEW =>                      'Новый',
            Status::NEW_BY_CUSTOMER =>          'Новый созданный заказчиком',
            Status::ESTIMATE =>                 'Составлена смена',
            Status::PART_ISSUE =>               'Частично выданы товары',
            Status::ISSUE =>                    'Товары выданы полностью',
            Status::PART_RETURN =>              'Частично возращены товары',
            Status::RETURN =>                   'Товары возращены полностью',
            Status::COMPLETED =>                'Завершен',
            Status::CANCELLED =>                'Отменен',
            Status::CANCELLED_BY_CUSTOMER =>    'Отменен Заказчиком'
        ];
    }

    public static function statusName($status): string
    {
        return ArrayHelper::getValue(self::statusList(), $status);
    }

    public static function orderName(Order $order): string
    {
        $responsible_name= (empty($order->responsible_name))?'':'('.$order->responsible_name.')';
        return Html::encode(date('Y-m-d',$order->date_begin). ' ' . $order->name . $responsible_name);
    }


}