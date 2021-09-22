<?php

namespace rent\forms\Shop\Search;

use rent\entities\Client\Site;
use rent\entities\Shop\Brand;
use rent\entities\Shop\Category;
use rent\entities\Shop\Characteristic;
use rent\forms\CompositeForm;
use yii\helpers\ArrayHelper;

/**
 * @property ValueForm[] $values
 */
class SearchForm extends CompositeForm
{
    public $text;
    public $category;
    public $brand;
    public $on_site;
    public $site;

    public function __construct(array $config = [])
    {
        $this->values = array_map(function (Characteristic $characteristic) {
            return new ValueForm($characteristic);
        }, Characteristic::find()->orderBy('sort')->all());

        if (\Yii::$app->settings->site) {
            $this->site=\Yii::$app->settings->site->id;
        }

        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['text'], 'string'],
            [['category', 'brand','on_site','site'], 'integer'],
        ];
    }

    public function categoriesList(): array
    {
        return ArrayHelper::map(Category::find()->andWhere(['>', 'depth', 0])->orderBy('lft')->asArray()->all(), 'id', function (array $category) {
            return ($category['depth'] > 1 ? str_repeat('-- ', $category['depth'] - 1) . ' ' : '') . $category['name'];
        });
    }

    public function brandsList(): array
    {
        return ArrayHelper::map(Brand::find()->orderBy('name')->asArray()->all(), 'id', 'name');
    }

    public function sitesList(): array
    {
        return ArrayHelper::map(Site::find()->orderBy('domain')->asArray()->all(), 'id','name');
    }

    public function formName(): string
    {
        return '';
    }

    protected function internalForms(): array
    {
        return ['values'];
    }
    public function attributeLabels()
    {
        return [
            'text' => 'Поисковый запрос',
            'category' => 'Категория',
            'on_site' => 'Опубликованы',
            'site' => 'Сайт'
        ];
    }
}
