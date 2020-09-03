<?php

namespace rent\forms\Shop;

use rent\entities\Shop\Order\Item\OrderItem;
use rent\entities\Shop\Product\Modification;
use rent\entities\Shop\Product\Product;
use rent\helpers\OrderHelper;
use rent\helpers\PriceHelper;
use yii\base\Model;
use yii\helpers\ArrayHelper;

class AddToCartForm extends Model
{
    public $product_id;
    public $qty;
    public $type;

    private $_product;

    public function __construct(Product $product, $config = [])
    {
        $this->_product = $product;
//        $this->product_id=$product->id;
        $this->qty = 1;
        parent::__construct($config);
    }

    public function rules(): array
    {
        return array_filter([
//            $this->_product->modifications ? ['modification', 'required'] : false,
            [['qty','type'], 'required'],
//            ['quantity', 'integer', 'max' => $this->_product->getQuantity()],
        ]);
    }
//
//    public function modificationsList(): array
//    {
//        return ArrayHelper::map($this->_product->modifications, 'id', function (Modification $modification) {
//            return $modification->code . ' - ' . $modification->name . ' (' . PriceHelper::format($modification->price ?: $this->_product->price_new) . ')';
//        });
//    }
    public function typeList():array
    {
        return [OrderItem::TYPE_RENT=>'Аренда',OrderItem::TYPE_SALE=>'Продажа'];
    }
}