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
 * @property integer $contact_id
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
    public $contact_id;

    public $note;

    public function __construct($config = [])
    {
        $this->delivery = new DeliveryForm();
        $this->responsible_id=\Yii::$app->user->id;
        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [[ 'name','date_begin','contact_id'], 'required'],
            [['responsible_id','date_begin', 'date_end','contact_id'], 'integer'],
            [['date_begin', 'date_end'], 'validateDate'],
            [['responsible_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['responsible_id' => 'id']],
            [['name','note'], 'string'],
            [['name'],'string', 'max' => 100],
            [['code'],'string', 'max' => 50],
        ];
    }

    protected function internalForms(): array
    {
        return ['delivery'];
    }

    public function attributeLabels()
    {
        return [
            'name' => 'Имя заказа',
            'date_begin' => 'Дата начала мероприятия',
            'date_end' => 'Окончание',
            'note'=>'Примечание',
            'responsible_id' => 'Менеджер',
            'current_status' => 'Статус',
            'contact_id' => 'Заказчик',
        ];
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