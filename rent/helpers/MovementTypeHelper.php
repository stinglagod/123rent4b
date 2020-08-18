<?php

namespace rent\helpers;

use rent\entities\Shop\Product\Movement\Action;
use rent\entities\Shop\Product\Movement\Movement;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class MovementTypeHelper
{
    public static function movementTypeList(): array
    {
        return [
            Movement::TYPE_INCOMING     => 'Приход',
            Movement::TYPE_RESERVE      => 'Бронь',
            Movement::TYPE_RENT_PUSH    => 'Выдача проката',
            Movement::TYPE_RENT_PULL    => 'Возрат проката',
            Movement::TYPE_SALE         => 'Продажа',
            Movement::TYPE_REPAIRS_PUSH => 'Отправка в ремонт',
            Movement::TYPE_REPAIRS_PULL => 'Возрат из ремонта',
            Movement::TYPE_WRITE_OFF    => 'Списание',
            Movement::TYPE_CORRECT      => 'Корректировка',
        ];
    }

    public static function movementTypeName($typeMovement_id): string
    {
        return ArrayHelper::getValue(self::movementTypeList(), $typeMovement_id);
    }

//    public static function statusLabel($typeMovement_id): string
//    {
//        switch ($typeMovement_id) {
//            case Product::STATUS_DRAFT:
//                $class = 'label label-default';
//                break;
//            case Product::STATUS_ACTIVE:
//                $class = 'label label-success';
//                break;
//            default:
//                $class = 'label label-default';
//        }
//
//        return Html::tag('span', ArrayHelper::getValue(self::statusList(), $status), [
//            'class' => $class,
//        ]);
//    }

}