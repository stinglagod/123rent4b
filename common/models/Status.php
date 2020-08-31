<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "status".
 *
 * @property int $id
 * @property string $name
 * @property string $shortName
 * @property int $hand
 * @property int $order
 * @property int $action_id
 *
 * @property Order[] $orders
 * @property Action $action
 */
class Status extends \yii\db\ActiveRecord
{
    const NEW=1;            //При создании заказа
    const NEWFRONTEND=11;   //При создании заказа посетителем
    const SMETA=2;          //При добавлении товара
    const PARTISSUE=3;      //Частично выданы товары
    const ISSUE=4;          //Товары выданы полностью
    const PARTRETURN=7;     //Частично возращены товары
    const RETURN=5;         //Товары возращены полностью
    const CLOSE=6;          //Закрыт
    const CANCELORDER=9;    //Отмернен
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'status';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['hand', 'order', 'action_id'], 'integer'],
            [['name', 'shortName'], 'string', 'max' => 100],
            [['action_id'], 'exist', 'skipOnError' => true, 'targetClass' => Action::className(), 'targetAttribute' => ['action_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'shortName' => Yii::t('app', 'Short Name'),
            'hand' => Yii::t('app', 'Hand'),
            'order' => Yii::t('app', 'Порядок'),
            'action_id' => Yii::t('app', 'Action ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrders()
    {
        return $this->hasMany(Order::className(), ['status_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAction()
    {
        return $this->hasOne(Action::className(), ['id' => 'action_id']);
    }
}
