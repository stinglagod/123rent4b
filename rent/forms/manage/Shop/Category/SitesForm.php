<?php

namespace rent\forms\manage\Shop\Category;

use rent\entities\Client\Site;
use rent\entities\Shop\Category\Category;
use yii\base\Model;
use yii\helpers\ArrayHelper;

class SitesForm extends Model
{
    public $others = [];

    public function __construct(Category $category = null, $config = [])
    {
        if ($category) {
            $this->others = ArrayHelper::getColumn($category->siteAssignments, 'site_id');
        }
        parent::__construct($config);
    }

    public function sitesList(): array
    {
        return ArrayHelper::map(Site::find()->andWhere(['status'=>Site::STATUS_ACTIVE])->orderBy('name')->asArray()->all(), 'id', 'name');
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