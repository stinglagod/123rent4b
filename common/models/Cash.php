<?php

namespace common\models;

use common\models\protect\MyActiveRecord;
use Yii;

/**
 * This is the model class for table "{{%cash}}".
 *
 * @property int $id
 * @property string $dateTime
 * @property double $sum
 * @property string $created_at
 * @property string $updated_at
 * @property int $autor_id
 * @property int $product_id
 * @property int $lastChangeUser_id
 * @property string $note
 * @property string $payer
 *
 * @property Client $client
 * @property User $lastChangeUser
 * @property User $user
 * @property OrderCash[] $orderCashes
 * @property Order[] $orders
 */
class Cash extends MyActiveRecord
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
            [['dateTime','created_at', 'updated_at'], 'safe'],
            [['sum'], 'number'],
            [['autor_id', 'lastChangeUser_id', 'client_id','cashType_id'], 'integer'],
            [['note','payer'], 'string', 'max' => 255],
            [['client_id'], 'exist', 'skipOnError' => true, 'targetClass' => Client::className(), 'targetAttribute' => ['client_id' => 'id']],
            [['lastChangeUser_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['lastChangeUser_id' => 'id']],
            [['autor_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['cashType_id'], 'exist', 'skipOnError' => true, 'targetClass' => CashType::className(), 'targetAttribute' => ['cashType_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'dateTime' => Yii::t('app', 'Дата и время'),
            'sum' => Yii::t('app', 'Сумма'),
            'autor_id' => Yii::t('app', 'Создал'),
            'lastChangeUser_id' => Yii::t('app', 'Изменил'),
            'client_id' => Yii::t('app', 'Client ID'),
            'created_at' => Yii::t('app', 'Создано'),
            'updated_at' => Yii::t('app', 'Отредактировано'),
            'cashType_id' => Yii::t('app', 'Вид платежа'),
            'note' => Yii::t('app', 'Примечание'),
            'payer' => Yii::t('app', 'Плательщик'),

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
    public function getAutor()
    {
        return $this->hasOne(User::className(), ['id' => 'autor_id']);
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCashType()
    {
        return $this->hasOne(CashType::class, ['id' => 'cashType_id']);
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if (empty($this->dateTime)) {
                $this->dateTime=date('Y-m-d H:i:s');
            }
            return true;
        } else {
            return false;
        }
    }

//    public function afterSave($insert, $changedAttributes)
//    {
//        parent::afterSave($insert, $changedAttributes);
//
//    }

    /**
     * Обновляем статус брось при получении платежа
     *
     */
//    private function updateStatusHardRent()
//    {
//        $order=$this->orders[0];
//        $orderProducts=$order->orderProducts;
//        if ($order->getPaid()>0) {
//            foreach ($orderProducts as $orderProduct) {
//                if ($orderProduct->type<>OrderProduct::COLLECT) {
//                    $status=$orderProduct->getStatus();
//                    if (!(key_exists(1,$status))) {
////                        $movement=new Movement();
////                        $movement->qty=(-1*$qty);
////                        $movement->action_id=$action_id;
////                        $movement->product_id=$this->product_id;
//                }
////
////                if
//            }
//            //ставим статус для всех товаров заббронировано
//        } else {
//            //ставим убираем статус забронировано
//
//        }
//    }
}
