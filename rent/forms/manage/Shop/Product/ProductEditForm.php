<?php

namespace rent\forms\manage\Shop\Product;

use rent\entities\Shop\Brand;
use rent\entities\Shop\Characteristic;
use rent\entities\Shop\Product\Product;
use rent\forms\CompositeForm;
use rent\forms\manage\MetaForm;
use yii\helpers\ArrayHelper;
use Yii;

/**
 * @property PriceSaleForm $priceSale
 * @property PriceRentForm $priceRent
 * @property PriceCostForm $priceCost
 * @property PriceCompensationForm $priceCompensation
 * @property MetaForm $meta
 * @property CategoriesForm $categories
 * @property TagsForm $tags
 * @property ValueForm[] $values
 */
class ProductEditForm extends CompositeForm
{
    public $brandId;
    public $code;
    public $name;
    public $description;
    public $onSite;

    private $_product;

    public function __construct(Product $product, $config = [])
    {
        $this->brandId = $product->brand_id;
        $this->code = $product->code;
        $this->name = $product->name;
        $this->description = $product->description;
        $this->onSite = $product->on_site;
        $this->meta = new MetaForm($product->meta);
        $this->categories = new CategoriesForm($product);
        $this->tags = new TagsForm($product);
        $this->values = array_map(function (Characteristic $characteristic) use ($product) {
            return new ValueForm($characteristic, $product->getValue($characteristic->id));
        }, Characteristic::find()->orderBy('sort')->all());
        $this->_product = $product;

        $this->priceSale = new PriceSaleForm($product);
        $this->priceRent = new PriceRentForm($product);
        $this->priceCost = new PriceCostForm($product);
        $this->priceCompensation = new PriceCompensationForm($product);
        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['code', 'name'], 'required'],
            [['brandId'], 'integer'],
            [['onSite'], 'boolean'],
            [['code', 'name'], 'string', 'max' => 255],
            [['code'], 'unique', 'targetClass' => Product::class, 'filter' => $this->_product ? ['<>', 'id', $this->_product->id] : null],
            ['description', 'string'],
        ];
    }
    public function attributeLabels()
    {
        return [
            'code' => 'Код',
            'name' => 'Название',
            'description' => 'Описание',
            'status'=>'Статус',
            'onSite'=>'Публикация на сайте',
        ];
    }

    public function brandsList(): array
    {
        return ArrayHelper::map(Brand::find()->orderBy('name')->asArray()->all(), 'id', 'name');
    }

    protected function internalForms(): array
    {
        return ['priceSale','priceRent','priceCost','priceCompensation','meta', 'categories', 'tags', 'values'];
    }
}