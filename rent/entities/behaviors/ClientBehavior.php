<?php

namespace rent\entities\behaviors;

use Yii;
use yii\base\Behavior;
use yii\base\Event;
use yii\db\ActiveRecord;
use rent\entities\User\User;

class ClientBehavior extends Behavior
{

    public function events(): array
    {
        return [
            ActiveRecord::EVENT_BEFORE_INSERT => 'onBeforeSave',
            ActiveRecord::EVENT_BEFORE_UPDATE => 'onBeforeSave',
        ];
    }

    public function onBeforeSave(Event $event): void
    {
        $model = $event->sender;

        if (Yii::$app->id=='app-console') return;

//        if ($user=User::findOne(Yii::$app->user->id)) {
//            $model->setAttribute('client_id',$user->client_id);
//        }
    }
}