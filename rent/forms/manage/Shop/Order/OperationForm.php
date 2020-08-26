<?php

namespace rent\forms\manage\Shop\Order;

use yii\base\Model;

class OperationForm extends Model
{
    public $item_id;
    public $operation_id;
    public $qty;

    public function __construct(array $config = [])
    {
        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['item_id', 'operation_id','qty'], 'required'],
            [['item_id', 'operation_id','qty'], 'integer'],
            [['qty'], 'integer', 'min' => 1],
        ];
    }

    public function attributeLabels()
    {
        return [
            'item_id' => 'позиция',
            'operation_id' => 'Телефон',
            'qty' => 'Количество',
        ];
    }
}