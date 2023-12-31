<?php

namespace rent\forms\manage\Shop\Product;

use rent\entities\Client\Site;
use rent\entities\Shop\Category\Category;
use rent\entities\Shop\Product\Product;
use yii\base\Model;
use yii\helpers\ArrayHelper;

class SitesForm extends Model
{
    public $main;
    public $others = [];

    public function __construct(Product $product = null, $config = [])
    {
        if ($product) {
            $this->main = $product->site_id;
            $this->others = ArrayHelper::getColumn($product->siteAssignments, 'site_id');
        }
        parent::__construct($config);
    }

    public function sitesList(): array
    {
        return ArrayHelper::map(Site::find()->select(['id','concat(name,\' (\',domain,\' )\') as name'])->andWhere(['status'=>Site::STATUS_ACTIVE])->orderBy('name')->asArray()->all(), 'id', 'name');
    }

    public function rules(): array
    {
        return [
            ['others', 'each', 'rule' => ['integer']],
            ['others', 'default', 'value' => []],
        ];
    }
    public function attributeLabels()
    {
        return [
            'others' => 'Сайты',
        ];
    }

}