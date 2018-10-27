<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%cash}}".
 *
 * @property int $id
 * @property string $dateTime
 * @property double $sum
 * @property int $user_id
 * @property int $lastChangeUser_id
 * @property int $client_id
 *
 * @property Client $client
 * @property User $lastChangeUser
 * @property User $user
 * @property OrderCash[] $orderCashes
 * @property Order[] $orders
 */
class Cash extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%cash}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['dateTime'], 'safe'],
            [['sum'], 'number'],
            [['user_id', 'lastChangeUser_id', 'client_id'], 'integer'],
            [['client_id'], 'exist', 'skipOnError' => true, 'targetClass' => Client::className(), 'targetAttribute' => ['client_id' => 'id']],
            [['lastChangeUser_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['lastChangeUser_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'dateTime' => Yii::t('app', 'Date Time'),
            'sum' => Yii::t('app', 'Sum'),
            'user_id' => Yii::t('app', 'User ID'),
            'lastChangeUser_id' => Yii::t('app', 'Last Change User ID'),
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLastChangeUser()
    {
        return $this->hasOne(User::className(), ['id' => 'lastChangeUser_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderCashes()
    {
        return $this->hasMany(OrderCash::className(), ['cash_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrders()
    {
        return $this->hasMany(Order::className(), ['id' => 'order_id'])->viaTable('{{%order_cash}}', ['cash_id' => 'id']);
    }
}
