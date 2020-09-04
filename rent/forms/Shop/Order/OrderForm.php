<?php

namespace rent\forms\Shop\Order;

use rent\forms\CompositeForm;

/**
 * @property DeliveryForm $delivery
 * @property CustomerForm $customer
 */
class OrderForm extends CompositeForm
{
    public $note;
    public $date_begin;
    public $date_end;

    public function __construct( array $config = [])
    {
        $this->customer = new CustomerForm();
        $this->date_begin=time();
        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['date_begin'], 'required'],
            [['date_begin','date_end'], 'integer'],
            [['note'], 'string'],
        ];
    }

    protected function internalForms(): array
    {
        return ['customer'];
    }
}