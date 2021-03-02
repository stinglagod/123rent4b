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

    /**
     * Выводим список операций, которые можем сделать напрямую на сайте
     * @return array
     */
    public static function movementTypeHandList(): array
    {
        return [
            Movement::TYPE_INCOMING     => 'Приход',
            Movement::TYPE_WRITE_OFF    => 'Списание',
            Movement::TYPE_CORRECT      => 'Корректировка',
        ];
    }

    public static function movementTypeName($typeMovement_id): string
    {
        return ArrayHelper::getValue(self::movementTypeList(), $typeMovement_id);
    }

    public static function getTypeIconHtml($type_id):string
    {
        switch ($type_id) {
            case Movement::TYPE_INCOMING:
                $class = 'fa fa-arrow-up text-green';
                break;
            case Movement::TYPE_RESERVE:
                $class = 'fa fa-arrow-down text-red';
                break;
            case Movement::TYPE_RENT_PUSH:
                $class = 'fa fa-arrow-down text-red';
                break;
            case Movement::TYPE_RENT_PULL:
                $class = 'fa fa-arrow-up  text-green';
                break;
            case Movement::TYPE_SALE:
                $class = 'fa fa-arrow-down text-red';
                break;
            case Movement::TYPE_REPAIRS_PUSH:
                $class = 'fa fa-arrow-down text-red';
                break;
            case Movement::TYPE_REPAIRS_PULL :
                $class = 'fa fa-arrow-up  text-green';
                break;
            case Movement::TYPE_WRITE_OFF:
                $class = 'fa fa-arrow-down text-red';
                break;
            case Movement::TYPE_CORRECT:
                $class = 'fa fa-arrow-down text-red';
                break;
            default:
                $class = '';
        }

        return Html::tag('span', '', [
            'class' => $class,
            'title' => ArrayHelper::getValue(self::movementTypeList(), $type_id)
        ]);
    }

    public function isPull($typeMovement_id):bool
    {
        return  ($typeMovement_id==Movement::TYPE_INCOMING) or
                ($typeMovement_id==Movement::TYPE_RENT_PULL) or
                ($typeMovement_id==Movement::TYPE_REPAIRS_PULL);
    }
    public function isPush($typeMovement_id):bool
    {
        return  ($typeMovement_id==Movement::TYPE_RENT_PUSH) or
            ($typeMovement_id==Movement::TYPE_SALE) or
            ($typeMovement_id==Movement::TYPE_REPAIRS_PUSH) or
            ($typeMovement_id==Movement::TYPE_WRITE_OFF);
    }
}