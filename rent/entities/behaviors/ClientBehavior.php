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


        if (Yii::$app->params['siteId']) {
            if (($model->canGetProperty('site_id')and $model->getAttribute('site_id')==null)) $model->setAttribute('site_id',Yii::$app->params['siteId']);
        }

        if (Yii::$app->id=='app-console') return;

        if (($model->canGetProperty('autor_id')and $model->getAttribute('autor_id')==null)) $model->setAttribute('autor_id',\Yii::$app->user->id);
        if (($model->canGetProperty('author_id')and $model->getAttribute('author_id')==null)) $model->setAttribute('author_id',\Yii::$app->user->id);
        if ($model->canGetProperty('lastChangeUser_id')) $model->setAttribute('lastChangeUser_id',\Yii::$app->user->id);



    }


}