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

        if (($model->canGetProperty('site_id')and $model->getAttribute('site_id')==null and (Yii::$app->settings->site))) {
            $model->setAttribute('site_id',Yii::$app->settings->site->id);
        }
        if (($model->canGetProperty('client_id')and $model->getAttribute('client_id')==null)) $model->setAttribute('client_id',Yii::$app->settings->getClientId());

        if (Yii::$app->id=='app-console') return;

        if (($model->canGetProperty('autor_id')and $model->getAttribute('autor_id')==null)) $model->setAttribute('autor_id',\Yii::$app->user->id);
        if (($model->canGetProperty('author_id')and $model->getAttribute('author_id')==null)) $model->setAttribute('author_id',\Yii::$app->user->id);
        if ($model->canGetProperty('lastChangeUser_id')) $model->setAttribute('lastChangeUser_id',\Yii::$app->user->id);
        if (($model->canGetProperty('responsible_id')and $model->getAttribute('responsible_id')==null)) $model->setAttribute('responsible_id',\Yii::$app->user->id);
        if (($model->canGetProperty('responsible_name')and $model->getAttribute('responsible_name')==null)) {
            if ($user=User::findOne(\Yii::$app->user->id)) {
                $model->setAttribute('responsible_name',$user->getShortName());
            }
        }

    }

    public static function find($all=false)
    {
        if ($all) {
            return parent::find();
        } else {
            return parent::find()->where(['client_id' => Yii::$app->settings->getClientId()]);
        }
    }

}