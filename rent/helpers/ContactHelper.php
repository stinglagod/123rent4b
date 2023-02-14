<?php

namespace rent\helpers;

use rent\entities\Client\Client;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class ContactHelper
{
    public static function statusList(): array
    {
        return [
            Client::STATUS_DELETED => 'Помечен на удаление',
            Client::STATUS_NOT_ACTIVE => 'Не активный',
            Client::STATUS_ACTIVE => 'Активный',
        ];
    }

    public static function statusName($status): string
    {
        return ArrayHelper::getValue(self::statusList(), $status);
    }

    public static function statusLabel($status): string
    {
        switch ($status) {
            case Client::STATUS_NOT_ACTIVE:
                $class = 'label label-default';
                break;
            case Client::STATUS_ACTIVE:
                $class = 'label label-success';
                break;
            case Client::STATUS_DELETED:
                $class = 'label label-danger';
                break;
            default:
                $class = 'label label-default';
        }

        return Html::tag('span', ArrayHelper::getValue(self::statusList(), $status), [
            'class' => $class,
        ]);
    }
}