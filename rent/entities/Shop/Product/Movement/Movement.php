<?php

namespace rent\entities\Shop\Product\Movement;

use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use rent\entities\Shop\Order\Item\OrderItem;
use rent\entities\Shop\Product\Product;
use rent\forms\manage\Shop\Product\ProductCreateForm;
use rent\helpers\MovementTypeHelper;
use rent\useCases\manage\Shop\ProductManageService;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use rent\entities\behaviors\ClientBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * @property integer $id
 * @property string $name
 * @property integer $date_begin
 * @property integer $date_end
 * @property integer $qty
 * @property integer $product_id
 * @property integer $type_id
 * @property integer $site_id
 * @property integer $depend_id
 * @property integer $active
 * @property integer $readOnly
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $autor_id
 * @property integer $lastChangeUser_id
 * @property integer $order_item_id
 * @property integer $client_id
 * @property string $comment
 *
 * @property Movement $depend
 * @property Product $product
 * @property OrderItem $orderItem
 * @property Balance[] $balances
 * @property Movement[] $children
 *
 * @method ActiveQuery find(bool $all)
**/

class Movement extends ActiveRecord
{
    const TYPE_INCOMING = 1;        //приход на склад
    const TYPE_RESERVE = 2;         //бронирование
    const TYPE_RENT_PUSH =3;        //выдать товар в прокат
    const TYPE_RENT_PULL = 4;       //получить товар с проката
    const TYPE_SALE = 5;            //продажа
    const TYPE_REPAIRS_PUSH = 6;    //в ремонт
    const TYPE_REPAIRS_PULL = 7;    //из ремонта
    const TYPE_WRITE_OFF = 8;       //списание
    const TYPE_CORRECT=9;           //корректировка



//    private $service;

//    public function __construct($service, array $config = [])
//    {
//        parent::__construct($service,$config);
//    }

    public static function create(int $begin,int $end=null, int $qty, int $productId=null, int $type_id, int $active,int $dependId=null, string $comment = null): self
    {
        $movement = new static();
        $movement->date_begin=$begin;
        $movement->date_end=$end;
        $movement->qty=$qty;
        $movement->product_id=$productId;
        $movement->type_id=$type_id;
        $movement->active=$active;
        $movement->depend_id=$dependId;
        $movement->comment=$comment;
        return $movement;
    }
    public function edit(int $begin, int $end=null,int $qty, int $productId, int $type_id, string $comment): void
    {
        $this->date_begin=$begin;
        $this->date_end=$end;
        $this->qty=$qty;
        $this->product_id=$productId;
        $this->type_id=$type_id;
        $this->comment=$comment;
    }
    public function isIdEqualTo($id): bool
    {
        return $this->id == $id;
    }
    public function isSale():bool
    {
        return $this->type_id==self::TYPE_SALE;
    }

    private function canPush($qty=null):bool
    {
        if ($this->type_id!=self::TYPE_RESERVE) return false;

        $qty = empty($qty)?$this->qty:$qty;
        $qtySum=0;
        foreach ( $this->children as $child) {
            if (($child->type_id==self::TYPE_RENT_PUSH)and($child->active)) {
                $qtySum+=$child->qty;
            }
        }
        if (($this->qty - $qtySum) < $qty) {
            return false;
//            throw new \DomainException('You cannot issue more than the reserved item');
        }
        return true;
    }
    private function canPull($qty=null):bool
    {
        if ($this->type_id!=self::TYPE_RESERVE) return false;
        $qty = empty($qty)?$this->qty:$qty;
        $qtyPull=0;
        $qtyPush=0;
        foreach ( $this->children as $child) {
            if ($child->active) {
                if ($child->type_id==self::TYPE_RENT_PULL) {
                    $qtyPull+=$child->qty;
                } elseif ($child->type_id==self::TYPE_RENT_PUSH) {
                    $qtyPush+=$child->qty;
                }
            }
        }
        if (($qtyPush - $qtyPull) < $qty) {
            return false;
//            throw new \DomainException('You cannot get more than the issue item');
        }
        return true;

    }
    private function canPullRepairs($qty=null):bool
    {
        if ($this->type_id!=self::TYPE_REPAIRS_PULL) return false;
        $qty = empty($qty)?$this->qty:$qty;
        $qtyPull=0;
        $qtyPush=0;
        foreach ( $this->children as $child) {
            if ($child->active) {
                if ($child->type_id==self::TYPE_RENT_PULL) {
                    $qtyPull+=$child->qty;
                }
            }
        }
        if (($this->depend->qty - $qtyPull) < $qty) {
            return false;
//            throw new \DomainException('You cannot get more than the repairs item');
        }
        return true;

    }

    public function getDepend(): ActiveQuery
    {
        return $this->hasOne(Movement::class, ['id' => 'depend_id']);
    }
    public function getProduct(): ActiveQuery
    {
        return $this->hasOne(Product::class, ['id' => 'product_id']);
    }
    public function getBalances(): ActiveQuery
    {
        return $this->hasMany(Balance::class, ['movement_id' => 'id']);
    }
    public function getChildren(): ActiveQuery
    {
        return $this->hasMany(Movement::class, ['depend_id' => 'id']);
    }
    public function getOrderItem(): ActiveQuery
    {
        return $this->hasOne(OrderItem::class, ['id' => 'order_item_id']);
    }


##############################################
    public static function tableName(): string
    {
        return '{{%shop_movements}}';
    }

    public function behaviors(): array
    {
        return [
            ClientBehavior::class,
            TimestampBehavior::class,
            [
                'class' => SaveRelationsBehavior::class,
                'relations' => ['balances','depend','depend.balances'],
            ],
        ];
    }

    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }


    public function beforeSave($insert)
    {
        $this->fillFields();
        $this->checkData();
        $this->changeBalance();
        return parent::beforeSave($insert);
    }


    /**
     * Заполняем поля по умолчанию
     */
    private function fillFields():void
    {
        if (empty($this->name)) {
            $this->name=MovementTypeHelper::movementTypeName($this->type_id);
            if ($this->order_item_id) {
                $this->name.='. Заказ: №' . $this->orderItem->order->id . ' ' . $this->orderItem->order->name;
            }

        }
    }
    /**
     * Проверяем заполненость данных. Необходимо соблюдения след. условий:
     * 1. У Брони (TYPE_RESERVE) всегда должно быть заполнено дата начала и дата конца, кроме продажных товаров
     * 2. У Выдачи прок. товара(TYPE_RENT_PUSH) должна быть зависимость от движения с типом брони(TYPE_RESERVE)
     * 3. У Получении прок. товара(TYPE_RENT_PULL) должна быть зависимость от движения с типом выдачи товара(TYPE_RENT_PUSH)
     * 4. У возрат товара из ремеонта(TYPE_REPAIRS_PULL) должна быть зависимость от движения с типом отправка в ремонт(TYPE_REPAIRS_PUSH)
     * @return bool
     */
    private function checkData():bool
    {
        if ($this->getOldAttribute('readOnly') and ($this->readOnly)) throw new \DomainException('Cannot be edited readOnly movement');

        switch ($this->type_id) {
            case self::TYPE_INCOMING:
                break;
            case self::TYPE_RESERVE:
                if (!$this->isNewRecord) break;
//                if ($this->children) break;
//                if (empty($this->date_end)) throw new \DomainException('The date_end empty for RESERVE');

                break;
            case self::TYPE_RENT_PUSH:
                if ($this->depend_id) {
                    if ($this->depend->type_id!=self::TYPE_RESERVE)
                        throw new \DomainException('The type_id by depend fail for RENT_PUSH');
                } else {
                    throw new \DomainException('The depend_id empty for RENT_PUSH');
                }
                break;
            case self::TYPE_SALE:
                if ($this->depend_id) {
                    if ($this->depend->type_id!=self::TYPE_RESERVE)
                        throw new \DomainException('The type_id by depend fail for TYPE_SALE');
                } else {
                    throw new \DomainException('The depend_id empty for TYPE_SALE');
                }
            case self::TYPE_RENT_PULL:
                if ($this->depend_id) {
                    if ($this->depend->type_id!=self::TYPE_RESERVE)
                        throw new \DomainException('The type_id by depend fail for RENT_PULL');
                } else {
                    throw new \DomainException('The depend_id empty for RENT_PULL');
                }
                break;
            case self::TYPE_REPAIRS_PUSH:
                break;
            case self::TYPE_REPAIRS_PULL:
                if ($this->depend_id) {
                    if ($this->depend->type_id!=self::TYPE_REPAIRS_PUSH)
                        throw new \DomainException('The type_id by depend fail for REPAIRS_PUSH');
                } else {
                    throw new \DomainException('The depend_id empty for REPAIRS_PULL');
                }
                break;
            case self::TYPE_WRITE_OFF:
                break;
        }
        return true;
    }
    /**
     * Изменение остатков по движению
     */
    private function changeBalance(): void
    {
        if ($this->readOnly) return;
        if (empty($this->product_id)) return;
        if ($this->active) {
            switch ($this->type_id) {
                case self::TYPE_INCOMING:
                    //Приход
                    //1. Добавляем в баланс приход товара с типом self::TYPE_INCOMING
                    $this->balances=[$this->addBalance($this->date_begin,$this->qty)];
                    break;
                case self::TYPE_RESERVE:
//                    var_dump('changeBalance::TYPE_RESERVE');
                    //Резервирование товара
                    //0. Если есть активные дети, то балансом управляют дети
//                    if (!$this->canChangeReserve) break;
                    if ($this->haveActiveChildren()) break;
                    //1. Проверяем есть ли на эти даты свободное кол-во
                    if (!$this->product->canReserve($this->date_begin,$this->date_end,$this->qty)) throw new \DomainException('На складе нет такого количества для бронирования товара: '.$this->product->id.' '.$this->product->name);
                    //2. Добавляем уход товара на начало период с типом self::TYPE_RESERVE
                    //3. Добавляем приход товара на начало период с типом self::TYPE_RESERVE
                    $balances[]=$this->addBalance($this->date_begin,$this->qty*(-1));
                    if ($this->date_end)
                        $balances[]=$this->addBalance($this->date_end,$this->qty);
                    $this->balances=$balances;
                    break;
                case self::TYPE_RENT_PUSH:
                    //Выдача прокатного товара
                    //1. Дата выдачи должна быть дата начало и датой конца брони
                    if (!(
                        ($this->date_begin>=$this->depend->date_begin)and
                        (($this->date_begin<=$this->depend->date_end))
                    )) throw new \DomainException('Не заполнена дата окончания аренды');
                    //2. Проверяем можешь ли мы уменьшить на это кол-во. Для этого надо проверить 2 условия:
                    //2.1. Сколько уже выданно в рамках зависимой брони. И не будет ли превышения
                    if (!$this->depend->canPush($this->qty)) throw new \DomainException('Не можете выдать больше чем забронировано. Товар'.$this->depend->product->id.' '.$this->depend->product->name);
                    //2.2. Есть ли реально на складе такое кол-во товара на дату выдачи
                    if (!$this->product->canPushRent($this->date_begin,$this->date_end,$this->qty,$this->depend_id)) throw new \DomainException('На складе нет такого количества для аренды товара: '.$this->product->id.' '.$this->product->name);
                    //3. Находим в балансе уход товара с типом self::TYPE_RESERVE и уменьшаем на кол-во выдачи.
                    $this->updateReserve();
                    // Если в итоге будем = 0 удаляем уход
                    //4. Добавляем уход с типом self::TYPE_RENT_PUSH
                    $this->balances=[$this->addBalance($this->date_begin,$this->qty*(-1))];
                    break;
                case self::TYPE_RENT_PULL:
                    //Возрат прокатного товара
                    //1. Проверить условие, что бы вернули не больше чем забрали
                    if (!$this->depend->canPull($this->qty)) throw new \DomainException('Не можете вернуть больше чем забрали. Товар'.$this->depend->product->id.' '.$this->depend->product->name);
                    //2. Убираем из баланса зависимую бронь на кол-во возращенного товара
                    $this->updateReserve();
                    //3. Добавляем в баланс кол-во возращенного товара
                    $this->balances=[$this->addBalance($this->date_end,$this->qty)];
                    break;
                case self::TYPE_SALE:
                    //Выдача продажого товара
                    //1. Проверяем есть кол-во товаров на дату
                    if (!$this->product->canPushSale($this->date_begin,$this->qty,$this->depend_id)) throw new \DomainException('На складе нет такого количества для продажи товара: '.$this->product->id.' '.$this->product->name);
                    //2. Находим в балансе уход товара с типом self::TYPE_RESERVE и уменьшаем на кол-во выдачи.
                    $this->updateReserve();
                    //3. Добавляем в баланс уход товара
                    $this->balances=[$this->addBalance($this->date_begin,$this->qty*(-1))];
                    break;
                case self::TYPE_REPAIRS_PUSH:
                    //Отправка товара в ремонт
                    //1. Проверяем можем ли мы выдать такое кол-во товаров
                    if (!$this->product->canPushSale($this->date_begin,$this->qty)) throw new \DomainException('Not in stock for repairs');
                    //2. Добавляем в баланс уход товара
                    $this->balances=[$this->addBalance($this->date_begin,$this->qty*(-1))];
                    break;
                case self::TYPE_REPAIRS_PULL:
                    //Возрат товара из ремонта
                    //1. Проверяем можем ли мы вернуть такое кол-во товаров. Нельзя вернуть больше чем отправили в ремонт
                    if (!$this->canPullRepairs()) throw new \DomainException('You cannot get more than the repairs item');
                    //2. Добавляем в балан приход товара
                    $this->balances=[$this->addBalance($this->date_begin,$this->qty*(-1))];
                    break;
                case self::TYPE_WRITE_OFF:
                    //Списание товара
                    //1. Проверяем есть ли такое кол-во товаров.
                    if (!$this->product->canPushSale($this->date_begin,$this->qty)) throw new \DomainException('Not in stock for write off');
                    //2. Добавляем в баланс уход товара
                    $this->balances=[$this->addBalance($this->date_begin,$this->qty*(-1))];
                    break;
                case self::TYPE_CORRECT:
                    //Корректировка на дату. Все движения до этой даты деактивируются
                    $this->addCorrect($this->date_begin,$this->qty);
            }
        } else {
            $this->balances=[];
        }
    }
    private function addBalance(int $dateTime,int $qty,int $type_id=null, string $comment=null): Balance
    {
        if (empty($type_id)) {
            $type_id=$this->type_id;
        }
        return Balance::create($dateTime,$this->product_id,$qty,$type_id,$comment);
    }

    private function haveActiveChildren():bool
    {
        foreach ($this->children as $child) {
            if ($child->active) return true;
        }
        return false;
    }
    public function isPush():bool
    {
        return (($this->type_id==self::TYPE_RENT_PUSH) or ($this->type_id==self::TYPE_SALE));
    }
    private function updateReserve():void
    {
        $parent=$this->depend;
        $balances=$parent->balances;
        //Ищем сколько уже выдано возращенно
        $qtyPush=0;//сколько выданно
        $qtyPull=0;//сколько возращено
        foreach ($parent->children as $child) {
            if ($this->isPush()) {
                foreach ($child->balances as $balance) {
                    $qtyPush+=$balance->qty*(-1);
                }
            } elseif ($child->type_id==self::TYPE_RENT_PULL) {
                foreach ($child->balances as $balance) {
                    $qtyPull+=$balance->qty;
                }
            }
        }
        //редактируем движения
        if ($this->type_id==self::TYPE_RENT_PUSH) {
             if (($leftToIssue=$parent->qty-$this->qty-$qtyPush)>=0) {
                 foreach ($balances as $balance) {
                     if (($balance->qty<0)and($balance->qty!=$leftToIssue)) {
                         if ($leftToIssue==0) {
                             $balance->delete();
                             unset($balance);
                         } else {
                             $balance->qty=$leftToIssue*(-1);
                             $balance->save();
//                             var_dump($leftToIssue);
                         }
                     }

                 }

             }
        } else if ($this->type_id==self::TYPE_RENT_PULL) {
            if (($leftToGet=$parent->qty-$this->qty-$qtyPull)>=0) {
                foreach ($balances as $balance) {
                    if (($balance->qty>0)and($balance->qty!=$leftToGet)) {
                        if ($leftToGet==0) {
                            $balance->delete();
                            unset($balance);
                        } else {
                            $balance->qty=$leftToGet;
                            $balance->save();
                        }
                    }
                }
            }
        } else if ($this->type_id==self::TYPE_SALE) {
            if (($leftToIssue=$parent->qty-$this->qty-$qtyPush)>=0) {
                foreach ($balances as $balance) {
                    if (($balance->qty<0)and($balance->qty!=$leftToIssue)) {
                        if ($leftToIssue==0) {
                            $balance->delete();
                            unset($balance);
                        } else {
                            $balance->qty=$leftToIssue*(-1);
                            $balance->save();
                        }
                    }

                }

            }
        }
    }

    public function deactive(): void
    {
        if (!$this->active) {
            throw new \DomainException('Movement is already deactive.');
        }
        $this->active=false;
    }

    public function addCorrect(int $begin, int $qty)
    {
        //1. Деактивируем все движения младше $begin
        if ($movements=Movement::find()->andWhere(['product_id'=>$this->product->id])->andWhere(['<','date_begin',$begin])->andWhere(['active'=>true])->orderBy('date_begin')->all()) {
//            var_dump($movements);exit;
            /** @var Movement $movement */
            foreach ($movements as $movement) {
                if (($movement->date_end) and ($movement->date_end>$begin)) {
                    throw new \DomainException('There are movements with an end date later than the correct date');
                }
                $movement->deactive();
                $movement->save();
            }
        }
        //2. Добавляем движение приход на дату $begin
        $this->balances=[$this->addBalance($this->date_begin,$this->qty)];

    }

}