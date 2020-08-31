<?php

namespace rent\forms\manage\Shop\Order\Item;

use rent\entities\Shop\Order\Item\OrderItem;
use yii\base\Model;

/**
 * @property string $name
 * @property float $price
 * @property int $qty
 * @property int $is_montage
 * @property int $period_qty
 * @property string $note
 *
 *
 **/
class ItemForm extends Model
{
    public $name;
    public $price;
    public $qty;
    public $is_montage;
    public $note;
    public $period_qty;

    public function __construct(OrderItem $item=null, array $config = [])
    {
        if ($item) {

            $this->name=$item->name;
            $this->price=$item->price;
            $this->qty=$item->qty;
            $this->is_montage=$item->is_montage;
            $this->note=$item->note;
//            $this->period=$item->periodData->qty;
            $this->period_qty=$item->period_qty;
        }

        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['name'], 'required'],
            [['name','note'], 'string'],
            [['qty','is_montage','period_qty'], 'integer'],
            [['qty','period_qty','price'], 'integer', 'min'=>1],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => 'Название',
            'price' => 'Цена',
            'qty' => 'Количество',
            'is_montage' => 'Есть монтаж',
            'period' => 'Период',
            'note' => 'Примечание',
        ];
    }
}