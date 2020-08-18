<?php

namespace rent\entities\Shop\Order;

use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use rent\entities\Client\Site;
use rent\entities\Shop\Order\Item\BlockData;
use rent\entities\Shop\Order\Item\OrderItem;
use rent\entities\User\User;
use rent\entities\Shop\Order\DeliveryData;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\Json;
use rent\entities\behaviors\ClientBehavior;
use yii\behaviors\TimestampBehavior;

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
 * @property OrderItem[] $blocks
 * @property Payment[] $payments
 * @property Status[] $statuses
 * @property ResponsibleHistory[] $responsibleHistory
 * @property Site $site
 * @property User $responsible
 */
class Order extends ActiveRecord
{
    public $customerData;
    public $deliveryData;
    public $statuses = [];
    public $responsibleHistory = [];

    public static function create(
        $responsible_id,
        string $name,
        $code,
        int $date_begin,
        int $date_end,
        CustomerData $customerData,
        DeliveryData $deliveryData,
        array $items,
        float $cost,
        string $note,
        bool $createCustomer=false): self
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
        $order->addStatus($createCustomer?Status::NEW_BY_CUSTOMER:Status::NEW);
        if ($responsible_id) $order->changeResponsible($responsible_id);
        return $order;
    }

    public function edit(
        $responsible_id,
        string $name,
        $code,
        int $date_begin,
        int $date_end,
        CustomerData $customerData,
        DeliveryData $deliveryData,
        string $note): void
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

    public function complete(): void
    {
        if ($this->isCompleted()) {
            throw new \DomainException('Order is already completed.');
        }
        $this->canBeCompleted(true);

        $this->addStatus(Status::COMPLETED);
    }

    public function canBeCompleted($exception=false):bool
    {
        //TODO: написать условия завершения заказа
        return true;
    }

    public function cancel($reason): void
    {
        if ($this->isCancelled()) {
            throw new \DomainException('Order is already cancelled.');
        }
        $this->canBeCancel(true);
        $this->cancel_reason = $reason;
        $this->addStatus(Status::CANCELLED);
    }

    public function canBeCancel($exception=false):bool
    {
        //TODO: написать условия отмены заказа
        return true;
    }

    public function getTotalCost(): int
    {
        return $this->cost;
    }

    public function canBePaid(): bool
    {
        return $this->isClose();
    }

    public function isClose():bool
    {
        return ($this->isCancelled() or $this->isCompleted());
    }

    public function isNew(): bool
    {
        return (($this->current_status == Status::NEW) or ($this->current_status == Status::NEW_BY_CUSTOMER));
    }

    public function isPaid(): bool
    {
        return $this->cost == $this->paid;
    }


    public function isCompleted(): bool
    {
        return $this->current_status == Status::COMPLETED;
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

    private function addStatus($value): void
    {
        $this->statuses[] = new Status($value, time());
        $this->current_status = $value;
    }
    private function changeResponsible($responsible_id):void
    {
        if (!$responsible = User::findOne($responsible_id))
            throw new \DomainException('Don not find responsible.');
        $this->responsibleHistory[]= new ResponsibleHistory($responsible_id,$responsible->getShortName(),time());
        $this->responsible_id=$responsible_id;
    }

    public function removePayment($id): void
    {
        $payments = $this->payments;
        foreach ($payments as $i => $payment) {
            if ($payment->isIdEqualTo($id)) {
                unset($payments[$i]);
                $this->payments=$payments;
                return;
            }
        }
        throw new \DomainException('Payment is not found.');
    }
    public function editBlock($id,$name): void
    {
        $blocks = $this->blocks;
        foreach ($blocks as $i => $block) {
            if ($block->block_id==$id) {
                $block->editBlock($name);
                $this->blocks = $blocks;
                return;
            }
        }
        throw new \DomainException('Block is not found.');
    }
    public function removeBlock($id):void
    {
        $blocks = $this->blocks;
        foreach ($blocks as $i => $block) {
            if ($block->block_id==$id) {
                if (!$block->children) {
                    unset($blocks[$i]);
                    $this->blocks=$blocks;
                    return;
                } else {
                    throw new \DomainException('Block have children.');
                }
            }
        }
        throw new \DomainException('Block is not found.');
    }

    #############################################

    public function getResponsible(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id'=>'responsible_id']);
    }


    public function getItems(): ActiveQuery
    {
        return $this->hasMany(OrderItem::class, ['order_id' => 'id']);
    }

    public function getBlocks(): ActiveQuery
    {
        return $this->hasMany(OrderItem::class, ['order_id' => 'id'])->andWhere(['type_id'=>OrderItem::TYPE_BLOCK]);
    }

    public function getPayments(): ActiveQuery
    {
        return $this->hasMany(Payment::class, ['order_id' => 'id']);
    }

    public function getPaid():float
    {
        $sum=BalanceCash::find()->andWhere(['order_id'=>$this->id])->sum('sum');
        return $sum?:0;
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
                'relations' => ['items','payments','blocks'],
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

        return parent::beforeSave($insert);
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