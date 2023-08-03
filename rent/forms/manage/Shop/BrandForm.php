<?php

namespace rent\forms\manage\Shop;

use Yii;
use rent\entities\Shop\Brand;
use rent\forms\CompositeForm;
use rent\forms\manage\MetaForm;
use rent\validators\SlugValidator;

/**
 * @property MetaForm $meta;
 */
class BrandForm extends CompositeForm
{
    public $name;
    public $slug;
    public $meta;

    private $_brand;

    public function __construct(Brand $service = null, $config = [])
    {
        if ($service) {
            $this->name = $service->name;
            $this->slug = $service->slug;
            $this->meta = new MetaForm($service->meta);
            $this->_brand = $service;
        } else {
            $this->meta = new MetaForm();
        }
        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['name', 'slug'], 'required'],
            [['name', 'slug',], 'string', 'max' => 255],
            ['slug', SlugValidator::class],
            [['name', 'slug'], 'unique', 'targetClass' => Brand::class, 'filter' => $this->_brand ? ['<>', 'id', $this->_brand->id] : null]
        ];
    }

    public function internalForms(): array
    {
        return ['meta'];
    }

    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Название'),
            'slug' => Yii::t('app', 'Транслитерация'),
        ];
    }
}
