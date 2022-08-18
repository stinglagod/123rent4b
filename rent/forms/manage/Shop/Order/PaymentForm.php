<?php

namespace rent\forms\manage\Shop\Order;

use rent\entities\Shop\Order\Order;
use rent\entities\Shop\Order\Payment;
use rent\forms\CompositeForm;


/**
 * @property integer $dateTime
 * @property float $sum
 * @property integer $responsible_id
 * @property string $responsible_name
 * @property integer $type_id
 * @property string $note
 * @property integer $purpose_id
 * @property integer $payer_id
 *
 * @property PayerForm $payer
 */
class PaymentForm extends CompositeForm
{
    public $dateTime;
    public $sum;
    public $type_id;
    public $note;
    public $purpose_id;
    public $responsible_id;
    public $responsible_name;
    public $payer_id;

    public function __construct(Order $order=null,array $config = [])
    {
        $this->payer = new PayerForm();
        $this->purpose_id=Payment::POP_INCOMING;
        $this->dateTime=time();
        if ($order) {
            $this->payer->name=$order->customerData->name;
            $this->payer->email=$order->customerData->email;
            $this->payer->phone=$order->customerData->phone;
        }
        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['dateTime', 'sum','type_id','purpose_id'], 'required'],
            [['sum'], 'double', 'min'=>0.01],
            [['note'], 'string', 'max' => 255],
            [['dateTime','type_id','purpose_id'], 'integer'],
        ];
    }

    protected function internalForms(): array
    {
        return ['payer'];
    }

    public function attributeLabels()
    {
        return [
            'dateTime' => 'Дата',
            'type_id' => 'Тип',
            'purpose_id' => 'Назначение',
            'sum'=>'Сумма',
            'note'=>'Примечание',
            'order_id'=>'Заказ',
        ];
    }
}