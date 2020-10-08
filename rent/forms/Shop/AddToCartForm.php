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
            ['qty', 'integer', 'max' => $this->_product->getQuantity()],
        ]);
    }

    public function typeList():array
    {
        $typeList=[];
        if ($this->_product->priceRent) {
            $typeList[OrderItem::TYPE_RENT]='Аренда';
        }
        if ($this->_product->priceSale) {
            $typeList[OrderItem::TYPE_SALE] ='Продажа';
        }
        return $typeList;
    }
}