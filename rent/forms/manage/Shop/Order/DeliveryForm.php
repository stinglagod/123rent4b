<?php

namespace rent\forms\manage\Shop\Order;

use rent\entities\Shop\Order\Order;
use yii\base\Model;

class DeliveryForm extends Model
{
    public $address;

    public function __construct(Order $order=null, array $config = [])
    {
        if ($order) {
            $this->address = $order->deliveryData->address;
        }
        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['address'], 'required'],
            [['address'], 'string'],
        ];
    }
}