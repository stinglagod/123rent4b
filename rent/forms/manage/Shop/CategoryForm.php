<?php

namespace rent\forms\manage\Shop;

use rent\entities\Shop\Category\Category;
use rent\forms\CompositeForm;
use rent\forms\manage\MetaForm;
use rent\forms\manage\Shop\Category\SitesForm;
use rent\validators\SlugValidator;
use yii\helpers\ArrayHelper;

/**
 * @property MetaForm $meta;
 * @property SitesForm $sites;
 */
class CategoryForm extends CompositeForm
{
    public $name;
    public $slug;
    public $code;
    public $title;
    public $description;
    public $parentId;
    public $showWithoutGoods;
    public $onSite;

    public $_category;

    public function __construct(Category $category = null, $config = [])
    {
        if ($category) {
            $this->name = $category->name;
            $this->slug = $category->slug;
            $this->code = $category->code;
            $this->title = $category->title;
            $this->description = $category->description;
            $this->parentId = $category->parent ? $category->parent->id : null;
            $this->meta = new MetaForm($category->meta);
            $this->_category = $category;
            $this->sites = new SitesForm($category);
            $this->showWithoutGoods=$category->show_without_goods;
            $this->onSite = $category->on_site;
        } else {
            $this->meta = new MetaForm();
            $this->sites = new SitesForm();
        }
        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['name', 'slug','parentId'], 'required'],
            [['parentId'], 'integer'],
            [['onSite','showWithoutGoods'], 'boolean'],
            [['name',  'title','code'], 'string', 'max' => 255],
            [['description'], 'string'],
            ['slug', SlugValidator::class],
            [['slug'], 'unique', 'targetClass' => Category::class, 'filter' => $this->_category ? ['<>', 'id', $this->_category->id] : null],
//            [['parentId'], 'unique', 'targetClass' => Category::class, 'targetAttribute' => ['parentId' => 'id']]
        ];
    }

    public function attributeLabels()
    {
        return[
            'name'=>'Название',
            'parentId'=>'Родительский каталог',
            'slug'=>'Название латинскими буквами',
            'showWithoutGoods'=>'Выводить на сайте без товаров?',
            'onSite'=>'Публикация на сайте',
        ];
    }

    public function parentCategoriesList(): array
    {
        return ArrayHelper::map(Category::find()->orderBy('lft')->asArray()->all(), 'id', function (array $category) {
            return ($category['depth'] > 1 ? str_repeat('-- ', $category['depth'] - 1) . ' ' : '') . $category['name'];
        });
    }

    public function internalForms(): array
    {
        return ['meta','sites'];
    }
}