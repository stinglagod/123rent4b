<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%movement}}".
 *
 * @property int $id
 * @property string $dateTime
 * @property int $qty
 * @property int $product_id
 * @property int $action_id
 * @property int $user_id
 * @property int $lastChangeUser_id
 * @property int $client_id
 *
 * @property Action $action
 * @property Client $client
 * @property User $lastChangeUser
 * @property Product $product
 * @property User $user
 * @property OrderProductAction[] $orderProductActions
 * @property OrderProduct[] $orderProducts
 * @property Ostatok[] $ostatoks
 */
class Movement extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%movement}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['dateTime'], 'safe'],
            [['qty', 'product_id', 'action_id', 'user_id', 'lastChangeUser_id', 'client_id'], 'integer'],
            [['action_id'], 'exist', 'skipOnError' => true, 'targetClass' => Action::className(), 'targetAttribute' => ['action_id' => 'id']],
            [['client_id'], 'exist', 'skipOnError' => true, 'targetClass' => Client::className(), 'targetAttribute' => ['client_id' => 'id']],
            [['lastChangeUser_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['lastChangeUser_id' => 'id']],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['product_id' => 'id']],
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
            'qty' => Yii::t('app', 'Qty'),
            'product_id' => Yii::t('app', 'Product ID'),
            'action_id' => Yii::t('app', 'Action ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'lastChangeUser_id' => Yii::t('app', 'Last Change User ID'),
            'client_id' => Yii::t('app', 'Client ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAction()
    {
        return $this->hasOne(Action::className(), ['id' => 'action_id']);
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
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
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
    public function getOrderProductActions()
    {
        return $this->hasMany(OrderProductAction::className(), ['movement_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderProducts()
    {
        return $this->hasMany(OrderProduct::className(), ['id' => 'order_product_id'])->viaTable('{{%order_product_action}}', ['movement_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOstatoks()
    {
        return $this->hasMany(Ostatok::className(), ['movement_id' => 'id']);
    }
}
