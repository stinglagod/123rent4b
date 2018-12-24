<?php

namespace common\models;

use common\models\protect\MyActiveRecord;
use Yii;

/**
 * This is the model class for table "{{%order_product}}".
 *
 * @property int $id
 * @property int $order_id
 * @property string $type
 * @property int $product_id
 * @property string $name
 * @property int $set
 * @property int $qty
 * @property double $cost
 * @property string $dateBegin
 * @property string $dateEnd
 * @property int $period
 * @property int $periodType_id
 *
 * @property Order $order
 * @property PeriodType $periodType
 * @property Product $product
 * @property OrderProductAction[] $orderProductActions
 * @property Movement[] $movements
 */
class OrderProduct extends MyActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%order_product}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order_id', 'product_id', 'set', 'qty', 'period', 'periodType_id'], 'integer'],
            [['type'], 'string'],
            [['cost'], 'number'],
            [['dateBegin', 'dateEnd'], 'safe'],
            [['name'], 'string', 'max' => 100],
            [['order_id'], 'exist', 'skipOnError' => true, 'targetClass' => Order::className(), 'targetAttribute' => ['order_id' => 'id']],
            [['periodType_id'], 'exist', 'skipOnError' => true, 'targetClass' => PeriodType::className(), 'targetAttribute' => ['periodType_id' => 'id']],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['product_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'order_id' => Yii::t('app', 'Order ID'),
            'type' => Yii::t('app', 'Type'),
            'product_id' => Yii::t('app', 'Товар'),
            'name' => Yii::t('app', 'Name'),
            'set' => Yii::t('app', 'Set'),
            'qty' => Yii::t('app', 'Кол-во'),
            'cost' => Yii::t('app', 'Цена'),
            'dateBegin' => Yii::t('app', 'Date Begin'),
            'dateEnd' => Yii::t('app', 'Date End'),
            'period' => Yii::t('app', 'Period'),
            'periodType_id' => Yii::t('app', 'Period Type ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasOne(Order::className(), ['id' => 'order_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPeriodType()
    {
        return $this->hasOne(PeriodType::className(), ['id' => 'periodType_id']);
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
    public function getOrderProductActions()
    {
        return $this->hasMany(OrderProductAction::className(), ['order_product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMovements()
    {
        return $this->hasMany(Movement::className(), ['id' => 'movement_id'])->viaTable('{{%order_product_action}}', ['order_product_id' => 'id']);
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
//            $this->client_id=User::findOne(Yii::$app->user->id)->client_id;
            return parent::beforeSave($insert);
        } else {
            return false;
        }
    }
    public function afterSave($insert, $changedAttributes)
    {
        $this->updateMovements();
        parent::afterSave($insert, $changedAttributes);
    }

    /**
     * Обновляем все движения позиции
     */
    private function updateMovements()
    {
        if ($this->isNewRecord){
            $movement=new Movement();
            $movement->qty=$this->qty;
            $movement->action_id=Action::SOFTRENT;
            return $movement->save();
        }
        return true;
    }
}
