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
 * @property int $orderBlock_id
 * @property int $parent_id
 *
 * @property Order $order
 * @property PeriodType $periodType
 * @property Product $product
 * @property OrderProductAction[] $orderProductActions
 * @property Movement[] $movements
 * @property OrderBlock[] $orderBlocks
 */
class OrderProduct extends MyActiveRecord
{
    const RENT='rent';
    const SALE='sale';
    const SERVICE='service';
    const COLLECT='collect';
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
            [['order_id', 'product_id', 'set', 'qty', 'period', 'periodType_id','orderBlock_id','parent_id'], 'integer'],
            [['type'], 'string'],
            [['cost'], 'number'],
            [['dateBegin', 'dateEnd'], 'safe'],
            [['name'], 'string', 'max' => 100],
            [['order_id'], 'exist', 'skipOnError' => true, 'targetClass' => Order::className(), 'targetAttribute' => ['order_id' => 'id']],
            [['periodType_id'], 'exist', 'skipOnError' => true, 'targetClass' => PeriodType::className(), 'targetAttribute' => ['periodType_id' => 'id']],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['product_id' => 'id']],
            [['orderBlock_id'], 'exist', 'skipOnError' => true, 'targetClass' => OrderBlock::className(), 'targetAttribute' => ['orderBlock_id' => 'id']],
            [['parent_id'], 'exist', 'skipOnError' => true, 'targetClass' => OrderProduct::className(), 'targetAttribute' => ['parent_id' => 'id']],
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
            'type' => Yii::t('app', 'Тип'),
            'product_id' => Yii::t('app', 'Товар'),
            'name' => Yii::t('app', 'Name'),
            'set' => Yii::t('app', 'Set'),
            'qty' => Yii::t('app', 'Кол-во'),
            'cost' => Yii::t('app', 'Цена'),
            'dateBegin' => Yii::t('app', 'Начало'),
            'dateEnd' => Yii::t('app', 'Конец'),
            'period' => Yii::t('app', 'Период'),
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
    public function getParent()
    {
        return $this->hasOne(OrderProduct::className(), ['id' => 'parent_id']);
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
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderBlock()
    {
        return $this->hasOne(OrderBlock::className(), ['id' => 'orderBlock_id']);
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
        $this->updateMovements($insert);
        if ($this->parent_id==null) {
            $this->parent_id=$this->id;
            $this->save();
        }
        parent::afterSave($insert, $changedAttributes);
    }

    /**
     * Обновляем все движения позиции
     */
    private function updateMovements($insert)
    {
//      не нужны движения для услуг и для коллекций
        if (($this->set)or($this->product->productType==Product::SERVICE)) {
            return true;
        }
        if ($insert) {
            $movement=new Movement();
            $movement->qty=$this->qty;
            $movement->action_id=Action::SOFTRENT;
            $movement->product_id=$this->product_id;
            $movement->save();

            $this->link('movements',$movement);
        } else {
            $movement=$this->getMovements()->where(['action_id'=>Action::SOFTRENT])->one();
            $movement->qty=$this->qty;
//            $movement->product_id=$this->product_id;
            $movement->save();
        }
        return true;
    }

    /**
     * Добавляем движение товаров
     */
    public function addMovement($action_id,$qty,$date=null)
    {
        $movement=new Movement();
        $movement->qty=$qty;
        $movement->action_id=$action_id;
        $movement->product_id=$this->product_id;
        if (!empty($date))
            $movement->dateTime=$date;

        if ($movement->save()){
            $this->link('movements',$movement);
            return true;
        } else {
            return false;
        }

    }

    /**
     * @return int
     */
    public static function getDefaultSet()
    {
        return time();
    }
    public static function getDefaultName($empty=null)
    {
        if ($empty) {
            return '<Новая коллекция>';
        }else {
            return '<Произвольная позиция>';
        }

    }

    public function getName()
    {
        if ($this->type==OrderProduct::COLLECT) {
            return $this->name;
        } else {
            return $this->product->name;
        }
    }

    public function getThumb()
    {
        if ($this->type==OrderProduct::COLLECT) {
            return Yii::$app->request->baseUrl.'/20c20/img/nofoto-300x243.png';
        } else {
            return $this->product->getThumb(\common\models\File::THUMBSMALL);
        }
    }
}
