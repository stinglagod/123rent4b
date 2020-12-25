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
class PriceRentForm extends Model
{
    public $old;
    public $new;

    public function __construct(Product $product = null, $config = [])
    {
        if ($product) {
            $this->new = $product->priceRent_new;
            $this->old = $product->priceRent_old;
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
            'new' => 'Аренда',
            'old' => 'Аренда старая цена',
        ];
    }
}