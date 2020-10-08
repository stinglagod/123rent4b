<?php

namespace rent\forms\manage\Client\Site\MainPage;

use rent\entities\Client\File;
use rent\entities\Shop\Category;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;

/**
 * @property int $category
 **/

class CategoryForm extends Model
{
    public $category;

    public function __construct( $category=null, $config = [])
    {
        $this->category=$category?:null;

    }
    public function rules(): array
    {
        return [
            [['category'], 'integer'],
        ];
    }
    public function categoriesList(): array
    {
        return ArrayHelper::map(Category::find()->andWhere(['>', 'depth', 0])->orderBy('lft')->asArray()->all(), 'id', function (array $category) {
            return ($category['depth'] > 1 ? str_repeat('-- ', $category['depth'] - 1) . ' ' : '') . $category['name'];
        });
    }}