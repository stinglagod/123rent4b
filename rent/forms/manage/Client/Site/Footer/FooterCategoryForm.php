<?php

namespace rent\forms\manage\Client\Site\Footer;

use rent\entities\Client\File;
use rent\entities\Shop\Category\Category;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;

/**
 * @property \rent\entities\Shop\Category\Category $category
 * @property int $category_id
 **/

class FooterCategoryForm extends Model
{
    public $category;
    public $category_id;

    public function __construct( $category_id=null, $config = [])
    {
        if ($category_id) {
            $this->category=Category::findOne($category_id);
            $this->category_id=$category_id;
        }

    }
    public function rules(): array
    {
        return [
            [['category_id'], 'integer'],
        ];
    }
    public function categoriesList(): array
    {
        return ArrayHelper::map(Category::find()->andWhere(['>', 'depth', 0])->orderBy('lft')->asArray()->all(), 'id', function (array $category) {
            return ($category['depth'] > 1 ? str_repeat('-- ', $category['depth'] - 1) . ' ' : '') . $category['name'];
        });
    }}