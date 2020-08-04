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
    public $priceCost;

    public $priceRent_new;
    public $priceSale_new;

    private $_product;

    public function __construct(Product $product, $config = [])
    {
        $this->brandId = $product->brand_id;
        $this->code = $product->code;
        $this->name = $product->name;
        $this->description = $product->description;
        $this->meta = new MetaForm($product->meta);
        $this->categories = new CategoriesForm($product);
        $this->tags = new TagsForm($product);
        $this->values = array_map(function (Characteristic $characteristic) use ($product) {
            return new ValueForm($characteristic, $product->getValue($characteristic->id));
        }, Characteristic::find()->orderBy('sort')->all());
        $this->_product = $product;

        $this->priceRent_new=$product->priceRent_new;
        $this->priceSale_new=$product->priceSale_new;
        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['code', 'name'], 'required'],
            [['brandId'], 'integer'],
            [['code', 'name'], 'string', 'max' => 255],
            [['code'], 'unique', 'targetClass' => Product::class, 'filter' => $this->_product ? ['<>', 'id', $this->_product->id] : null],
            ['description', 'string'],
            [['priceCost','priceRent_new','priceSale_new'], 'double'],
        ];
    }
    public function attributeLabels()
    {
        return [
            'code' => 'Код',
            'name' => 'Название',
            'description' => 'Описание',
            'status'=>'Статус',
            'priceCost'=>'Себестоимость',
            'priceRent_new'=>'Аренда',
            'priceSale_new'=>'Продажа',
        ];
    }

    public function brandsList(): array
    {
        return ArrayHelper::map(Brand::find()->orderBy('name')->asArray()->all(), 'id', 'name');
    }

    protected function internalForms(): array
    {
        return ['meta', 'categories', 'tags', 'values'];
    }
}