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
 *
 *
 * @property CustomerForm $customer
 * @property DeliveryForm $delivery
 *
 */
class OrderCreateForm extends CompositeForm
{

    public $date_begin;
    public $date_end;
    public $responsible_id;
    public $name;
    public $code;

    public $note;

    public function __construct($config = [])
    {
        $this->customer = new CustomerForm();
        $this->delivery = new DeliveryForm();
        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [[ 'name','date_begin'], 'required'],
            [['responsible_id','date_begin', 'date_end'], 'integer'],
            [['date_begin', 'date_end'], 'validateDate'],
            [['responsible_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['responsible_id' => 'id']],
            [['name','note'], 'string'],
            [['name'],'string', 'max' => 100],
            [['code'],'string', 'max' => 50],
        ];
    }

    protected function internalForms(): array
    {
        return ['customer','delivery'];
    }

    public function validateDate()
    {
        if ($this->date_end) {
            if ($this->date_begin > $this->date_end){
                $this->addError('date_end','"Дата окончания", не может быть раньше "даты начала"');
            }
        }
    }
}