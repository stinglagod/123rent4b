<?php

namespace rent\entities\Shop\Order;

use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use phpDocumentor\Reflection\Types\This;
use rent\cart\CartItem;
use rent\entities\Client\Site;
use rent\entities\Shop\Order\Item\BlockData;
use rent\entities\Shop\Order\Item\OrderItem;
use rent\entities\Shop\Order\Item\PeriodData;
use rent\entities\Shop\Service;
use rent\entities\User\User;
use rent\entities\Shop\Order\DeliveryData;
use rent\forms\manage\Shop\Order\OrderCartForm;
use rent\helpers\OrderHelper;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use rent\entities\behaviors\ClientBehavior;
use yii\behaviors\TimestampBehavior;
use Yii;

/**
 * @property int $id
 * @property string $code
 * @property int $date_begin
 * @property int $date_end
 * @property string $name
 * @property int $cost
 * @property int $note
 * @property int $current_status
 * @property string $cancel_reason
 * @property CustomerData $customerData
 * @property DeliveryData $deliveryData
 * @property integer $responsible_id
 * @property string $responsible_name
 * @property integer $site_id
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $author_id
 * @property integer $lastChangeUser_id
 * @property integer $paid
 * @property integer $customer_id
 *
 * @property OrderItem[] $items
 * @property OrderItem[] $itemsWithoutBlocks
 * @property OrderItem[] $blocks
 * @property OrderItem[] $services
 * @property OrderItem[] $itemsDependByService
 * @property Payment[] $payments
 * @property Status[] $statuses
 * @property ResponsibleHistory[] $responsibleHistory
 * @property Site $site
 * @property User $responsible
 */
class Order extends ActiveRecord
{

    const OPERATION_ISSUE = 1;          //Выдача
    const OPERATION_RETURN = 2;         //Возрат

    const DEFAULT_BOOKING_TIME = 30*60*24*14;  // по умолчанию срок бронирования

    public $customerData;
    public $deliveryData;
    public $statuses = [];
    public $responsibleHistory = [];

    public static function create(
        $responsible_id,
        string $name,
        $code,
        int $date_begin,
        int $date_end=null,
        CustomerData $customerData,
        DeliveryData $deliveryData,
        array $items,
        float $cost,
        string $note,
        bool $createCustomer = null): self
    {
        $order = new static();
        $order->name = $name;
        $order->code = $code;
        $order->date_begin = $date_begin;
        $order->date_end = $date_end;
        $order->customerData = $customerData;
        $order->deliveryData = $deliveryData;
        $order->items = $items;
        $order->cost = $cost;
        $order->note = $note;
        $order->addStatus($createCustomer ? Status::NEW_BY_CUSTOMER : Status::NEW);
        if ($responsible_id) $order->changeResponsible($responsible_id);
        return $order;
    }

    public function edit(
        $responsible_id,
        string $name,
        $code,
        int $date_begin,
        $date_end=null,
        CustomerData $customerData,
        DeliveryData $deliveryData,
        string $note=''): void
    {
        $this->name = $name;
        $this->code = $code;
        $this->date_begin = $date_begin;
        $this->date_end = $date_end;
        $this->customerData = $customerData;
        $this->deliveryData = $deliveryData;
        $this->note = $note;
        if ($responsible_id) $this->changeResponsible($responsible_id);
    }
###Status
    public function canMakeNew($exception = false):bool
    {
        if ($exception) {
            if ($this->isNew()){
                throw new \DomainException('Снять бронь нельзя. Бронь уже снята ');
            }
            if ($this->isIssuedProduct()){
                throw new \DomainException('Снять бронь нельзя. По заказу есть выданные товары. ');
            }
            if ($this->hasPayments()) {
                throw new \DomainException('Снять бронь нельзя. Имеется оплата по заказу.');
            }
        }
        return (!$this->hasPayments()) and (!$this->isIssuedProduct()and (!$this->isNew()));
    }
    public function makeNew():void
    {
        if ($this->isNew()) {
            throw new \DomainException('Заказ уже со снятой бронью');
        }

        $this->canMakeNew(true);

        $this->addStatus(Status::isNew($this->statuses[0]->value)?$this->statuses[0]->value:Status::NEW);
    }

    public function estimate():void
    {
        if ($this->isEstimated()) {
            throw new \DomainException('Order is already estimate.');
        }

        $this->canBeEstimated(true);

        $this->addStatus(Status::ESTIMATE);
    }

    public function canBeEstimated($exception = false):bool
    {
        if ($exception) {
            if (!$this->isNew()){
                throw new \DomainException('Забронировать можно только новый заказ');
            }
            if (!$this->hasPayments()) {
                throw new \DomainException('Бронировать можно только после предоплаты.');
            }
        }
        return ($this->isNew() and $this->hasPayments());
    }

    public function complete(): void
    {
        if ($this->isCompleted()) {
            throw new \DomainException('Order is already completed.');
        }
        $this->canBeCompleted(true);

        $this->addStatus(Status::COMPLETED);
    }

    public function canBeCompleted($exception = false): bool
    {
        if ($exception) {
            if ($this->isPaid()) {
                throw new \DomainException('Заказ не оплачен полностью.');
            }
            if ($this->isNew()) {
                throw new \DomainException('Нельзя завершить новый заказ - только отмена');
            }
            if ($this->isCompleted()) {
                throw new \DomainException('Заказ уже завершен');
            }
            if ($this->isEstimated()) {
                throw new \DomainException('Нельзя завершить заказ со статусом "Составлена смета"');
            }
            if (!$this->isDebtByProduct()) {
                throw new \DomainException('Не все позиции заказа отданы(возращены)');
            }
        }
        return  (($this->isDebtByProduct())and
                (!$this->isPaid()and
                (!$this->isNew())and
                (!$this->isCompleted()) and
                (!$this->isEstimated())));
    }

    public function cancel($reason=''): void
    {
        if ($this->isCancelled()) {
            throw new \DomainException('Order is already cancelled.');
        }
        $this->canBeCancel(true);
        $this->cancel_reason = $reason;
        $this->addStatus(Status::CANCELLED);
    }

    public function canBeCancel($exception = false): bool
    {
        if ($exception) {
            if (!$this->isDebtByProduct()) {
                throw new \DomainException('Не все позиции заказа отданы(возращены)');
            }
            if ($this->hasPayments()) {
                throw new \DomainException('Нельзя отменить заказ с платежами');
            }
            if ($this->isCancelled()) {
                throw new \DomainException('Заказ уже отменен');
            }
        }
        return ($this->isDebtByProduct()and(!$this->hasPayments())and(!$this->isCancelled()));
    }
    public function isClose(): bool
    {
        return ($this->isCancelled() or $this->isCompleted());
    }

    public function isNew(): bool
    {
        return Status::isNew($this->current_status);
    }
    public function isCompleted(): bool
    {
        return $this->current_status == Status::COMPLETED;
    }

    public function isEstimated(): bool
    {
        return $this->current_status == Status::ESTIMATE;
    }

    public function isCancelled(): bool
    {
        return (($this->current_status == Status::CANCELLED) or ($this->current_status == Status::CANCELLED_BY_CUSTOMER));
    }

    public function canChangeStatus(Status $status): bool
    {
        //TODO: прописать условия когда можно или нельзя менять статус
        return true;
    }

    public function isIssuedProduct($full=false):bool
    {
        foreach ($this->itemsWithoutBlocks as $item) {
            if ($item->isIssued($full))
                return true;
        }
        return false;
    }
    public function isReserveProduct():bool
    {
        foreach ($this->itemsWithoutBlocks as $item) {
            if ($item->isReserved())
                return true;
        }
        return false;
    }
    public function isReturnedProduct($full=false):bool
    {
        if (empty($items=$this->itemsWithoutBlocks)) return true;

        foreach ( $items as $item) {
            if ($item->isReturned($full))
                return true;
        }
        return false;
    }
    public function isDebtByProduct():bool
    {
        foreach ($this->itemsWithoutBlocks as $item) {
            if (!$item->isCompleted()) return false;

        }
        return true;
    }
    private function addStatus($value): void
    {

        if ($this->current_status!=$value) {
            $this->statuses[] = new Status($value, time());
            $this->current_status = $value;
        }

        $operation=null;
        if ($items=$this->itemsWithoutBlocks) {
            switch ($value){
                case Status::isNew($value):                 //освобождаем от резервирования
                    $operation='clearMovement';
                    break;
                case Status::ESTIMATE:                      //Бронируем
                    $operation='reserve';
                    break;
                case Status::COMPLETED:                      //Завершаем
                    $operation='complete';
                    break;
                case Status::CANCELLED:                      //Отменяем
                    $operation='cancel';
                    break;
                default:
                    throw new \DomainException('Неопределена операция с товаром, просьба связаться с администратором');
            }
            foreach ($items as $item) {
                $item->$operation();
            }
            $this->itemsWithoutBlocks=$items;
        }
    }

    private function updateStatus():void
    {
        $status_id = Status::NEW;
        if ($this->statuses) {
            $status_id = Status::isNew($this->statuses[0]->value);
        }

        if ($items = $this->itemsWithoutBlocks) {
            $least_item = null;
            foreach ($items as $item) {
                if (empty($least_item)) {
                    $least_item = $item;
                    continue;
                }

                if ($item->current_status < $least_item->current_status) {
                    $least_item = $item;
                }
            }
            if (!Status::isNew($least_item->current_status)) {
                $status_id = $least_item->current_status;
            }
        }
        if ($this->current_status!=$status_id) {
            $this->statuses[] = new Status($status_id, time());
            $this->current_status = $status_id;
        }
    }
###Payments
    private $_cost;

    public function getTotalCost(): float
    {
        if (empty($this->_cost)) {
            $sum = 0;
            foreach ($this->blocks as $block) {
                $sum += $block->getCost();
            }
            $this->_cost = $sum;
        }

        return $this->_cost;
    }

    public function canBePaid(): bool
    {
        return $this->isClose();
    }

    public function isPaid(): bool
    {
        return $this->cost == $this->paid;
    }

    public function hasPayments():bool
    {
        return count($this->payments)>0;
    }

    public function removePayment($id): void
    {
        $payments = $this->payments;
        foreach ($payments as $i => $payment) {
            if ($payment->isIdEqualTo($id)) {
                unset($payments[$i]);
                $this->payments = $payments;
                return;
            }
        }
        throw new \DomainException('Payment is not found.');
    }
###Service
    public function hasService(Service $service): bool
    {
        /** @var OrderItem $item_service */
        foreach ($this->services as $item_service) {
            if ($item_service->service_id == $service->id) return true;
        }
        return false;
    }
    public function addService(Service $service): void
    {
        if ($this->hasService($service)) {
            throw new \DomainException('Услуга (' . $service->name . ') уже есть в заказе');
        }

        $services = $this->services;
        $item_service = OrderItem::createService($service);
        if ($service->is_depend) {
            $cost = 0;
            /** @var OrderItem $item */
            foreach ($this->itemsDependByService as $item) {
                $cost += $item->getCost() * $service->percent / 100;
            }
            $item_service->price = $cost;
        }
        $services[] = $item_service;
        $this->services = $services;
    }
###Responsible
    private function changeResponsible($responsible_id): void
    {
        if (!$responsible = User::findOne($responsible_id))
            throw new \DomainException('Don not find responsible.');
        $this->responsibleHistory[] = new ResponsibleHistory($responsible_id, $responsible->getShortName(), time());
        $this->responsible_id = $responsible_id;
    }

###Block
    public function addBlock($name): OrderItem
    {
        $blocks = $this->blocks;
        $block = OrderItem::createBlock($name);
        $blocks[] = $block;
        $this->blocks = $blocks;
        $this->updateBlocks($blocks);
        return $block;
    }

    public function editBlock($id, $name): void
    {
        $blocks = $this->blocks;
        foreach ($blocks as $i => $block) {
            if ($block->isIdEqualTo($id)) {
                $block->editBlock($name);
                $this->blocks = $blocks;
                return;
            }
        }
        throw new \DomainException('Block is not found.');
    }

    public function countBlocks(): int
    {
        return count($this->blocks);
    }

    public function moveBlockUp($id): void
    {
        $blocks = $this->blocks;
        foreach ($blocks as $i => $block) {

            if ($block->isIdEqualTo($id)) {

                if ($prev = $blocks[$i - 1] ?? null) {
                    $blocks[$i - 1] = $block;
                    $blocks[$i] = $prev;
                    $this->updateBlocks($blocks);
                }
                return;
            }
        }
        throw new \DomainException('Block is not found.');
    }

    public function moveBlockDown($id): void
    {
        $blocks = $this->blocks;
        foreach ($blocks as $i => $block) {
            if ($block->isIdEqualTo($id)) {
                if ($prev = $blocks[$i + 1] ?? null) {
                    $blocks[$i + 1] = $block;
                    $blocks[$i] = $prev;
                    $this->updateBlocks($blocks);
                }
                return;
            }
        }
        throw new \DomainException('Block is not found.');
    }

    private function updateBlocks(array $blocks): void
    {
        foreach ($blocks as $i => $block) {
            $block->setSort($i);
        }
        $this->blocks = $blocks;
    }



###Item
    public function addItem(CartItem $cartItem): void
    {
        $items = $this->items;
        $notInOrder = true;
        foreach ($items as $item) {
            if ($item->product) {
                if (($item->product->isIdEqualTo($cartItem->product->id)) and
                    ($item->parent->isIdEqualTo($cartItem->parent->id))) {
                    $item->qty += $cartItem->qty;
                    $notInOrder = false;
                }
            }
        }
        if ($notInOrder)
            $items[] = OrderItem::create($cartItem);

        $this->items = $items;
    }

    public function editItem($item_id, $name, $price, $qty, $period, $is_montage, $note): void
    {
        $items = $this->items;
        foreach ($items as $i => $item) {
            if ($item->isIdEqualTo($item_id)) {
                $item->name = $name;
                $item->price = $price;
                $item->qty = $qty;
                $item->periodData->qty = $period;
                $item->period_qty = $period;

                $item->is_montage = $is_montage;
                $item->note = $note;
                $this->items = $items;
                return;
            }
        }
        throw new \DomainException('Item is not found.');
    }

    public function removeItem($id): void
    {
        $items = $this->items;
        foreach ($items as $i => $item) {
            if ($item->isIdEqualTo($id)) {
                if (!$item->children) {
                    unset($items[$i]);
                    $this->items = $items;
                    return;
                } else {
                    throw new \DomainException('Item have children.');
                }
            }
        }

    }


    #############################################

    public function getResponsible(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'responsible_id']);
    }


    public function getItems(): ActiveQuery
    {
        return $this->hasMany(OrderItem::class, ['order_id' => 'id']);
    }

    public function getItemsWithoutBlocks(): ActiveQuery
    {
        return $this->hasMany(OrderItem::class, ['order_id' => 'id'])->andWhere(['<>','type_id', OrderItem::TYPE_BLOCK])->andWhere(['<>','type_id', OrderItem::TYPE_SERVICE]);
    }


    public function getBlocks(): ActiveQuery
    {
        return $this->hasMany(OrderItem::class, ['order_id' => 'id'])->andWhere(['type_id' => OrderItem::TYPE_BLOCK])->orderBy('sort');
    }

    public function getServices(): ActiveQuery
    {
        return $this->hasMany(OrderItem::class, ['order_id' => 'id'])->andWhere(['type_id' => OrderItem::TYPE_SERVICE])->orderBy('sort');
    }

    public function getPayments(): ActiveQuery
    {
        return $this->hasMany(Payment::class, ['order_id' => 'id']);
    }

    public function getPaid(): float
    {
        $sum = BalanceCash::find()->andWhere(['order_id' => $this->id])->sum('sum');
        return $sum ?: 0;
    }

    public function getPeriod(): PeriodData
    {
        if (empty($this->date_end)) {
            throw new \DomainException('Data end is empty.');
        }
        $days = OrderHelper::countDaysBetweenDates($this->date_begin, $this->date_end);
        return new PeriodData($days);
    }

    public function getItemsDependByService(): ActiveQuery
    {
        return $this->hasMany(OrderItem::class, ['order_id' => 'id'])->andWhere(['is_montage' => true]);
    }

    public function getBlocksForOperation(int $operation_id,array $order_ids=null): ActiveQuery
    {
        $query=$this->getItems()->andWhere(['in','current_status',$this->getStatuesByOperation($operation_id)]);
        if ($operation_id==self::OPERATION_RETURN) {
            $query->andWhere(['type_id'=>OrderItem::TYPE_RENT]);
        }
        if ($order_ids) {
            $query->andWhere(['in', 'parent_id', $order_ids]);
        }
        return $query;

    }
    private function getStatuesByOperation($operation_id):array
    {
        switch ($operation_id){
            case self::OPERATION_ISSUE:
                return [Status::ESTIMATE,Status::PART_ISSUE];
            case self::OPERATION_RETURN:
                return [Status::PART_ISSUE,Status::ISSUE,Status::PART_RETURN];
        }
        throw new \DomainException('Operation failed.');
    }

    public function getDateEnd():int
    {
        return $this->date_end?:($this->date_begin+self::DEFAULT_BOOKING_TIME);
    }

    ##########################################

    public static function tableName(): string
    {
        return '{{%shop_orders}}';
    }

    public function behaviors(): array
    {
        return [
            ClientBehavior::class,
            TimestampBehavior::class,
            [
                'class' => SaveRelationsBehavior::class,
                'relations' => ['items','payments','blocks','services','itemsWithoutBlocks'],
            ],
        ];
    }

    public function transactions(): array
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    public function afterFind(): void
    {
        $this->statuses = array_map(function ($row) {
            return new Status(
                $row['value'],
                $row['created_at']
            );
        }, Json::decode($this->getAttribute('statuses_json')));

        $this->responsibleHistory = array_map(function ($row) {
            return new ResponsibleHistory(
                $row['responsible_id'],
                $row['responsible_name'],
                $row['created_at']
            );
        }, Json::decode($this->getAttribute('responsibleHistory_json')));

        $this->customerData = new CustomerData(
            $this->getAttribute('customer_phone'),
            $this->getAttribute('customer_name'),
            $this->getAttribute('customer_email')
        );

        $this->deliveryData = new DeliveryData(
            $this->getAttribute('delivery_address')
        );

        parent::afterFind();
    }

    public function beforeSave($insert): bool
    {
        $this->setAttribute('statuses_json', Json::encode(array_map(function (Status $status) {
            return [
                'value' => $status->value,
                'created_at' => $status->created_at,
            ];
        }, $this->statuses)));

        $this->setAttribute('responsibleHistory_json', Json::encode(array_map(function (ResponsibleHistory $responsibleHistory) {
            return [
                'responsible_id' => $responsibleHistory->responsible_id,
                'responsible_name' => $responsibleHistory->responsible_name,
                'created_at' => $responsibleHistory->created_at,
            ];
        }, $this->responsibleHistory)));

        $this->setAttribute('customer_phone', $this->customerData->phone);
        $this->setAttribute('customer_name', $this->customerData->name);
        $this->setAttribute('customer_email', $this->customerData->email);

        $this->setAttribute('delivery_address', $this->deliveryData->address);

        if ($this->responsible_id) {
            $this->setAttribute('responsible_name', $this->responsible->getShortName());
        }

        $this->updateStatus();

        return parent::beforeSave($insert);
    }
    public static function find()
    {
        return parent::find()->where(['site_id' => Yii::$app->params['siteId']]);
    }

    public function attributeLabels()
    {
        return [
            'name' => 'Имя заказа',
            'date_begin' => 'Дата начала мероприятия',
            'date_end' => 'Окончание',
            'note'=>'Примечание',
            'responsible_id' => 'Менеджер'
        ];
    }
}