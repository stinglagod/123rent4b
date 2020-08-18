<?php

namespace rent\forms\manage\Shop\Order;

use rent\entities\Shop\Order\Order;
use rent\entities\User\User;
use rent\forms\CompositeForm;

/**
 * @property integer $date_begin
 * @property integer $date_end
 * @property integer $responsible_id
 * @property string $name
 * @property string $code
 * @property string $note
 *
 * @property CustomerForm $customer
 * @property DeliveryForm $delivery
 */
class OrderEditForm extends CompositeForm
{
    public $date_begin;
    public $date_end;
    public $responsible_id;
    public $name;
    public $code;

    public $note;

    public function __construct(Order $order, array $config = [])
    {
        $this->date_begin=$order->date_begin;
        $this->date_end=$order->date_end;
        $this->responsible_id=$order->responsible_id;
        $this->name=$order->name;
        $this->code=$order->code;
        $this->note = $order->note;
        $this->customer = new CustomerForm($order);
        $this->delivery = new DeliveryForm($order);
        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['responsible_id','date_begin', 'date_end'], 'integer'],
            //TODO: сделать условие что бы date_end было больше date_begin
            [['responsible_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['responsible_id' => 'id']],
            [['name','note'], 'string'],
            [['name'],'string', 'max' => 100],
            [['code'],'string', 'max' => 50   ],
        ];
    }

    protected function internalForms(): array
    {
        return ['customer','delivery'];
    }
    public function attributeLabels()
    {
        return [
            'name' => 'Имя заказа',
            'date_begin' => 'Дата начала мероприятия',
            'date_end' => 'Окончание',
            'note'=>'Примечание',
            'responsible_id' => 'Менеджер',
            'current_status' => 'Статус'
        ];
    }
}