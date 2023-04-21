<?php

namespace rent\helpers\support;

use rent\entities\Support\Task\Task;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class TaskHelper
{
    public static function statusList(): array
    {
        return [
            Task::STATUS_DELETED => 'Помечен на удаление',
            Task::STATUS_NEW => 'Не активный',
            Task::STATUS_ACTIVE => 'Активный',
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