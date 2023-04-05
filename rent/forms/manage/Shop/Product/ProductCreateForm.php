<?php

namespace rent\forms\manage\Shop\Product;

use rent\entities\Shop\Brand;
use rent\entities\Shop\Characteristic;
use rent\entities\Shop\Product\Product;
use rent\forms\CompositeForm;
use rent\forms\manage\MetaForm;
use yii\helpers\ArrayHelper;

/**
 * @property PriceSaleForm $priceSale
 * @property PriceRentForm $priceRent
 * @property PriceCostForm $priceCost
 * @property PriceCompensationForm $priceCompensation
 * @property MetaForm $meta
 * @property CategoriesForm $categories
  * @property PhotosForm $photos
 * @property TagsForm $tags
 * @property ValueForm[] $values
 * @property SitesForm $sites
 */
class ProductCreateForm extends CompositeForm
{
    public $brandId;
    public $code;
    public $name;
    public $description;
    public $onSite;


    public function __construct($config = [])
    {
        $this->priceSale = new PriceSaleForm();
        $this->priceRent = new PriceRentForm();
        $this->priceCost = new PriceCostForm();
        $this->priceCompensation = new PriceCompensationForm();
        $this->meta = new MetaForm();
        $this->categories = new CategoriesForm();
        $this->photos = new PhotosForm();
        $this->tags = new TagsForm();
        $this->values = array_map(function (Characteristic $characteristic) {
            return new ValueForm($characteristic);
        }, Characteristic::find()->orderBy('sort')->all());

        $this->code=Product::findNextCode();
        $this->sites = new SitesForm();
        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [[ 'name'], 'required'],
            [['code', 'name'], 'string', 'max' => 255],
            [['brandId'], 'integer'],
            [[ 'code'], 'required'],
            [['onSite'], 'boolean'],
            [['code'], 'unique',
                'targetClass' => Product::class,
                'filter' => ['client_id'=>\Yii::$app->settings->getClientId()],
                'message' => 'Код не уникальный'
            ],
            ['description', 'string'],
        ];
    }

    public function brandsList(): array
    {
        return ArrayHelper::map(Brand::find()->orderBy('name')->asArray()->all(), 'id', 'name');
    }

    protected function internalForms(): array
    {
        return ['priceSale','priceRent','priceCost','priceCompensation', 'meta','photos', 'categories', 'tags', 'values','sites'];
    }
    public function attributeLabels()
    {
        return[
            'name'=>'Название',
            'code'=>'Код',
            'description'=>'Описание',
            'photos'=>'Изображения',
            'onSite'=>'Публикация на сайте',
        ];
    }
}