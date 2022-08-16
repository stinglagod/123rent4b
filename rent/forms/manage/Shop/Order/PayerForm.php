<?php

namespace rent\forms\manage\Shop\Order;

use rent\entities\Shop\Order\Order;
use yii\base\Model;

class PayerForm extends Model
{
    public $phone;
    public $name;
    public $email;

    public function __construct(Order $order=null, array $config = [])
    {
        if ($order) {
            $this->phone = $order->customerData->phone;
            $this->name = $order->customerData->name;
            $this->email = $order->customerData->email;
        }

        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['name'], 'string', 'max' => 255],
            [['phone'], 'string', 'max' => 50],
            ['email', 'email'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'phone' => 'Телефон плательщика',
            'name' => 'Имя плательщика',
            'email' => 'Email плательщика',
        ];
    }
}