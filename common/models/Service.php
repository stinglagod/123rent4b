<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%services}}".
 *
 * @property int $id
 * @property string $name
 * @property int $percent
 * @property int $is_depend
 * @property int $defaultCost
 * @property int $client_id
 *
 * @property Client $client
 */
class Service extends protect\MyActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%service}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['percent', 'is_depend', 'defaultCost', 'client_id'], 'integer'],
            [['name'], 'string', 'max' => 100],
            [['client_id'], 'exist', 'skipOnError' => true, 'targetClass' => Client::className(), 'targetAttribute' => ['client_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Название'),
            'percent' => Yii::t('app', 'Процент от заказа'),
            'is_depend' => Yii::t('app', 'Зависит от позиций заказа?'),
            'defaultCost' => Yii::t('app', 'Цена по умолчанию'),
            'client_id' => Yii::t('app', 'Client ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClient()
    {
        return $this->hasOne(Client::className(), ['id' => 'client_id']);
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
//          Проверяем можно ли устанавилвать поле is_depend. Это полне может быть true только один раз в рамках одного клиента
            if (($this->is_depend) and(!($this->getOldAttribute('is_depend')))) {
                if (Service::find()->where(['client_id'=>User::findOne(\Yii::$app->user->id)->client_id])->andWhere(['is_depend'=>1])->one()) {
                    $session = Yii::$app->session;
                    $session->setFlash('error', 'Только одна услуга может зависить от позиций заказа');
                    return false;
                }
            }
            return true;
        } else {
            return false;
        }
    }

    /**
     * Возращает список услуг в рамках клиента
     * @return array|\yii\db\ActiveRecord[]
     */
    static public function getAll()
    {
        if (empty($client_id)) {
            $client_id=User::findOne(\Yii::$app->user->id)->client_id;
        }
        return Service::find()->where(['client_id'=>$client_id])->all();
    }

    static public function getDependService($client_id=null)
    {
        if (empty($client_id)) {
            $client_id=User::findOne(\Yii::$app->user->id)->client_id;
        }
        return Service::find()->where(['client_id'=>$client_id,'is_depend'=>1])->one();
    }

}
