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
    const RENT = 'rent';
    const SALE = 'sale';
    const SERVICE = 'service';
    const COLLECT = 'collect';

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
            [['order_id', 'product_id', 'set', 'qty', 'period', 'periodType_id', 'orderBlock_id', 'parent_id'], 'integer'],
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
//          проверяем можно ли редактировать
            if ($this->readOnly()) {
                $session = Yii::$app->session;
                $session->setFlash('error', 'Данную позицию нельзя редактировать');
                return false;
            }
            if ($this->parent_id <> $this->id) {
                $this->cost = null;
            }
//          Проверить есть ли такое кол-во на складе
//          Найти сколько уже забронировано
//          Найти какое кол-во есть на складе
            if ($this->check()===false) {
                return false;
            }

            return parent::beforeSave($insert);
        } else {
            return false;
        }
    }

    /**
     * Проверка кол-во, на даты. Перед соххранением
     *
     */
    public function check($newAction_id=null)
    {
        $newAction_id=($newAction_id)?$newAction_id:$this->order->status->action_id;
        if ($newAction_id==Action::SOFTRENT) {
            return true;
        }

        $oldQty=$this->getOldAttribute('qty');
        $ostatok=Product::getBalancById($this->product_id,$this->dateBegin,$this->dateEnd);
        if ($this->qty > ($ostatok+$oldQty)) {
            $session = Yii::$app->session;
            $session->setFlash('error', 'На складе нет такого кол-во товаров на эти даты. Доступно: '. $ostatok  );
            return false;
        }

        return true;
    }

    public function afterSave($insert, $changedAttributes)
    {
        $this->updateMovement($insert, $changedAttributes);
        if ($this->parent_id == null) {
            $this->parent_id = $this->id;
            $this->save();
        }
        parent::afterSave($insert, $changedAttributes);
    }

    /**
     * Обновляем все движения позиции
     */
    private function updateMovement($insert, $changedAttributes)
    {
//      не нужны движения для услуг и для коллекций
        if (($this->set) or ($this->product->productType == Product::SERVICE)) {
            return true;
        }
        $action_id = ($this->order->status->action_id) ? $this->order->status->action_id : Action::SOFTRENT;
//        if ($insert) {}
        $action=Action::findOne($action_id);
        if (($action_id==Action::SOFTRENT)or($action_id==Action::HARDRENT)) {
            $checkBalance=false;
        }

        //      Если поменялась дата начала
        if (key_exists('dateBegin', $changedAttributes)){
            $this->removeMovement($action_id);
            if ($action->antipod_id) {
                $this->removeMovement($action->antipod_id);
            }
            $this->addMovement($action_id,null,$this->dateBegin,$checkBalance);
        }
        if  (key_exists('dateEnd', $changedAttributes)) {
            if ($action->antipod_id) {
                $this->removeMovement($action->antipod_id);
                $this->addMovement($action->antipod_id, null, $this->dateEnd, false);
            }
        }
//      Если поменялось кол-во
        if (key_exists('qty', $changedAttributes)) {
//            Yii::error("================================найден в изменненных аттрибутах|".$changedAttributes['qty']."|");
            $qty = $this->qty ? $this->qty : 0;
            $checkBalance = true;
//            if ($insert) {
//                $checkBalance = false;
//            }




            if ($this->updMovement($action_id,$qty)) {
               if ($action->antipod_id) {
                   $newqty=$action->antipod->sing ? $qty : (-1 * $qty);
                   if (!($this->updMovement($action->antipod_id,$newqty))) {
                       $this->addMovement($action->antipod_id, $qty, $this->dateEnd, false);
                   }
               }
            } else {
                $this->addMovement($action_id, $qty, $this->dateBegin, $checkBalance);
            }
        }


        return true;
    }

    /**
     * Добавляем движение товара
     */
    public function addMovement($action_id, $qty = null, $date = null, $checkBalance = true, $recursion = true)
    {

        if (empty($qty)) {
            $qty = $this->qty;
        }


//      Если возрат или выдача товара, тогда меняем  статус заказа
        if ($action_id==Action::ISSUE) {
            $this->order->status_id=Status::ISSUE;
            $this->order->save();
            //Если выдача тогда смотрим какая выдача. Если прокатная, тогда меняем действие
            if ($this->type=='rent') {
                $action_id=Action::ISSUERENT;
            }
        } else if ($action_id==Action::RETURN) {
            $this->order->status_id=Status::RETURN;
            $this->order->save();
            //Если выдача тогда смотрим какая выдача. Если прокатная, тогда меняем действие
            if ($this->type=='rent') {
                $action_id=Action::RETURNRENT;
            }
        }

        $action = Action::findOne($action_id);
        //      проверка, а можно ли добавить движение
        if ($checkBalance) {
            $operationBalance = $this->getOperationBalance($action_id);
            if ($qty > $operationBalance) {
                Yii::error('Кол-во болььше чем можно');
                return false;
            }
        }

        $movement = new Movement();
        $movement->qty =$action->sing ? $qty : (-1 * $qty);
        $movement->action_id = $action_id;
        $movement->product_id = $this->product_id;

        if (empty($date)) {
            if ($this->dateBegin) {
                $movement->dateTime = $this->dateBegin;
            } else {
                $movement->dateTime = date('y-m-d');
            }
        } else {
            $movement->dateTime = $date;
        }

        // Если выдача, тогда уменьшаем снятий брони на кол-во выданных
        if ($action_id == Action::ISSUE) {
//            $this->updMovement(Action::UNSOFTRENT, -$qty,true);
            $this->updMovement(Action::UNHARDRENT, -$qty,true);
//            $this->addMovement(Action::UNSOFTRENT,$qty,$date,false, false);
            $this->addMovement(Action::UNHARDRENT,$qty,$date,false, false);

        }

        if ($movement->save()) {
            $this->link('movements', $movement);
            // Если есть антипод, тогда и делаем движение по антиподу
            if (($recursion) and ($action->antipod_id) ) {
                if ($this->addMovement($action->antipod_id, null, $this->dateEnd, false, false)) {
                    return true;
                } else {
                    return false;
                }
            }

            return true;
        } else {
            return false;
        }

    }

    /**
     * Удаление движения
     * @param null $action_id
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function removeMovement($action_id = null)
    {
        $movements = $this->getMovements();
        if ($action_id) {
            $movements = $movements->where(['action_id' => $action_id]);
        }
        $movements = $movements->all();

        foreach ($movements as $movement) {
            $this->unlink('movements', $movement, true);
            $movement->delete();
        }
    }

    /**
     * Изменение движения по action_id
     * Если $howmuch истина, тогда $newQty - указывается на сколько увеличить(уменьшить) значение. иначе
     * новое значениеё
     */

    public function updMovement($action_id, $newQty = null, $howmuch=false)
    {
        if (!($movement = $this->getMovements()->where(['action_id' => $action_id])->one())) {
            return false;
        }

        if ($howmuch) {
            $movement->qty=$movement->qty + $newQty;

            if ($movement->qty==0) {
                $movement->delete();
            }
        } else {

            if (empty($newQty)) {
                $newQty = $this->qty;
            } else {
                $action = Action::findOne($action_id);
                $newQty = $action->sing ? $newQty : (-1 * $newQty);
            }
            $movement->qty=$newQty;
        }
        return $movement->save();

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

    private static function getStatusByID($order_product_id)
    {
        $result = Movement::find()
            ->select([
                '{{movement}}.*',
                'SUM([[qty]]) as sum1'
            ])
            ->joinWith('orderProductActions')
            ->joinWith('action')
            ->where (['order_product_action.order_product_id'=>$order_product_id])
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

                if ((($item['action']['actionType_id']==ActionType::RESERVSOFT) or ($item['action']['actionType_id']==ActionType::RESERVHARD))) {
                    if (($item['action']['antipod_id'])) {
                        $booking=$qty;
                        $strRespone=$item['action']['shortName'];
                    }
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
                if ((self::getStatusParent($this->id))==false) {
                    $this->_status = (['text'=>$this->order->status->shortName]);
                } else {
                    $this->_status = self::getStatusParent($this->id);
                }

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

         //Если операция выдача/получение, тогда заменяем на выдача/получение прокатных
         $action_id=$this->getOperation($action_id);

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

    /**
     * Текущий баланс по позиции
     * $soft - учитывать ли мягкий резерв. По умолчанию не учитывать
     */
     public function getBalance($action_id=null,$date=null,$rentSoft=false,$rensHard=false)
     {
         $movements_id=$this->getMovements()->select(['id']);
         if ($action_id) {
             $movements_id=$movements_id->andWhere(['action_id'=>$action_id]);
         }
         $movements_id=$movements_id->column();

         $ostatok=Ostatok::find()->select('SUM([[qty]]) as sum1')->where(['in','movement_id',$movements_id]);
         if ($rentSoft===false) {
             $ostatok=$ostatok->andWhere(['<>','actionType_id',ActionType::RESERVSOFT]);
         }
         if ($rensHard===false) {
             $ostatok=$ostatok->andWhere(['<>','actionType_id',ActionType::RESERVHARD]);
         }
         if ($ostatok=$ostatok->asArray()->all()){
             return (int)$ostatok[0]['sum1'];
         }
         return 0;

     }

    public function beforeDelete()
    {
        foreach ( $this->movements as $movement) {
            $movement->delete();
        }

        return parent::beforeDelete();

    }
    /**
     * Можно ли редактировать запись
     */
    private $_readOnly;
    public function readOnly()
    {
        //нельзя редактировать, если уже есть выдача
        if (empty($this->_readOnly)) {
            if (($this->getBalance(Action::ISSUE)) or ($this->getBalance(Action::ISSUERENT))) {
                $this->_readOnly=true;
            } else {
                $this->_readOnly=false;
            }
        }
        return $this->_readOnly;
    }

    /**
     * Меняю операции в зависсмости от типа позици.(аренда, продажа)
     *
     */
    public function getOperation($action_id)
    {
        if ($action_id==Action::ISSUE) {
            if ($this->type=='rent') {
                return Action::ISSUERENT;
            }
        } else if ($action_id==Action::RETURN) {
            if ($this->type=='rent') {
                return Action::RETURNRENT;
            }
        } else {
            return $action_id;
        }
    }
}
