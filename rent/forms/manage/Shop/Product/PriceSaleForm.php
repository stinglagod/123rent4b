<?php

namespace rent\forms\manage\Shop\Product;

use rent\entities\Shop\Product\Product;
use rent\forms\manage\MetaForm;
use yii\base\Model;

/**
 * @property MetaForm $meta
 * @property CategoriesForm $categories
 * @property TagsForm $tags
 * @property ValueForm[] $values
 */
class PriceSaleForm extends Model
{
    public $old;
    public $new;

    public function __construct(Product $product = null, $config = [])
    {
        if ($product) {
            $this->new = $product->priceSale_new;
            $this->old = $product->priceSale_old;
        }
        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
//            [['new'], 'required'],
            [['old', 'new'], 'double', 'min' => 0],
        ];
    }

    public function attributeLabels()
    {
        return [
            'new' => 'Продажа',
            'old' => 'Продажа старая цена',
        ];
    }
}