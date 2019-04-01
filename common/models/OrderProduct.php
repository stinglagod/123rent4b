<?php

namespace common\models;

use common\models\protect\MyActiveRecord;
use Yii;
use yii\data\ActiveDataProvider;

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
            if ($this->parent_id<>$this->id) {
                $this->cost=null;
            }
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
            $movement->action_id=Action::SOFTRENT;
//          т.к. Аренда ставим минус всегда
            $movement->qty=-1*$this->qty;
            $movement->product_id=$this->product_id;
            $movement->save();

            $this->link('movements',$movement);
        } else {
            $movement=$this->getMovements()->where(['action_id'=>Action::SOFTRENT])->one();
//          т.к. Аренда ставим минус всегда
            $movement->qty=-1*$this->qty;
//            $movement->product_id=$this->product_id;
            $movement->save();
        }
        return true;
    }

    /**
     * Добавляем движение товаров
     */
    public function addMovement($action_id,$qty=null,$date=null)
    {
        $action=Action::findOne($action_id);
        if (empty($qty)) {
            $qty=$this->qty;
        }
        $movement=new Movement();
        $movement->qty=$action->sing?$qty:(-1*$qty);
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
    public function removeMovement ($action_id)
    {
        $movements = $this->getMovements()->where(['action_id'=>$action_id])->all();
        foreach ($movements as $movement) {
            $this->unlink('movements',$movement,true);
            $movement->delete();
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

    private static function getStatusByID($ordeer_product_id)
    {
        $result = Movement::find()
            ->select([
                '{{movement}}.*',
                'SUM([[qty]]) as sum1'
            ])
            ->joinWith('orderProductActions')
            ->joinWith('action')
            ->where (['order_product_action.order_product_id'=>$ordeer_product_id])
            ->groupBy('action_id')
            ->orderBy('action_id')
            ->asArray()
            ->all();
        $respone=array();
        $strRespone='';
        $booking=0;
        if ($result) {
            foreach ($result as $item) {
                $qty = ($item['sum1']<0)?-1*$item['sum1']:$item['sum1'];
                $respone[$item['action_id']]['name']=$item['action']['shortName'];
                $respone[$item['action_id']]['qty']= $qty;

                if (($item['action']['type']=='rentSoft') or ($item['action']['type']=='rentHard')) {
                    $booking=$qty;
                    $strRespone=$item['action']['shortName'];
                } else {
                    if ($booking==$qty) {
                        $strRespone=$item['action']['shortName'];
                    } else if($qty>0){
                        $strRespone=$item['action']['shortName']. ' - частично';
                    }
                }
            }
            $respone['text']=$strRespone;
        } else {
            return false;
        }
        return $respone;
    }
    private static function getStatusParent($parent_id)
    {
//      Выводим наименьший статус у потомков
        $id=null;
        $respone=false;
        $childs=OrderProduct::find()->where(['parent_id'=>$parent_id])->all();
        foreach ($childs as $child) {
            if ($status=self::getStatusByID($child->id)) {
                end($status);
                prev($status);
                $key=key($status);
                if (($key<$id)or($id==null)) {
                    $id=$key;
                    $respone['text']=$status['text'];
                }
            }
        }
        return $respone;
    }
    private $_status;

    public function getStatus()
    {
        if ($this->_status === null) {
            if ($this->type == OrderProduct::COLLECT) {

                $this->_status = self::getStatusParent($this->id);
            } else {
                $this->_status = self::getStatusByID($this->id);
            }
        }
        return $this->_status;
     }

    /**
     * Сколько можно выдать товара по данной операции
     */
     public function getOperationBalance($action_id)
     {
         //TODO: В случае если удаляются позиции, нужно проверить можно ли их удалить
         if ($action_id==0) {
             return $this->qty;
         }
         $action = Action::findOne($action_id);
         if (empty($action)){
             return false;
         }
         $status=$this->getStatus();
         $sequence=explode(',',$action->sequence);
         $qty=0;
         foreach ($sequence as $item) {
//                  Есть ли операция в статусе, после которой можно совершать текущую операцию
             if (array_key_exists ($item, $status)) {
                 $qty=$status[$item]['qty'];
//                      Есть ли текущая операция в статусе
                 if (array_key_exists ($action_id, $status)) {
//                          Какое- кол-во можно испльзвоать в оперциии
                     $qty-=$status[$action_id]['qty'];
                 }
             }
         }
         return $qty;
     }

     private $_summ;
    /**
     * Сумма позции с учетом аренды(продажи)
     */
     public function getSumm()
     {
         if (empty($this->_summ)) {
             $this->_summ=$this->cost*$this->qty;
             if ($this['type']==self::RENT) {
                 $this->_summ*=$this->period;
             }
         }
         return $this->_summ;
     }
}
