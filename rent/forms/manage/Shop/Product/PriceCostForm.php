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
class PriceCostForm extends Model
{
    public $cost;

    public function __construct(Product $product = null, $config = [])
    {
        if ($product) {
            $this->cost = $product->priceCost;
        }
        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
//            [['cost'], 'required'],
            [['cost'], 'double', 'min' => 0],
        ];
    }
    public function attributeLabels()
    {
        return [
            'cost' => 'Себестоимость',
        ];
    }

}