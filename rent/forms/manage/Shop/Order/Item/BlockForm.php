<?php

namespace rent\forms\manage\Shop\Order\Item;

use rent\entities\Shop\Order\Item\OrderItem;
use yii\base\Model;

class BlockForm extends Model
{
    public $name;

    public function __construct(OrderItem $block=null, array $config = [])
    {
        if ($block) {
            $this->name=$block->name;
        }

        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['name'], 'required'],
            [['name'], 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => 'Название блока',
        ];
    }
}