<?php

namespace rent\forms\manage\Shop\Product;

use rent\entities\Shop\Product\Product;
use rent\entities\Shop\Tag;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use Yii;

/**
 * @property array $newNames
 */
class TagsForm extends Model
{
    public $existing = [];
    public $textNew;

    public function __construct(Product $product = null, $config = [])
    {
        if ($product) {
            $this->existing = ArrayHelper::getColumn($product->tagAssignments, 'tag_id');
        }
        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            ['existing', 'each', 'rule' => ['string']],
            ['existing', 'default', 'value' => []],
            ['textNew', 'string'],
        ];
    }

    public function tagsList(): array
    {
        return ArrayHelper::map(Tag::find()->orderBy('name')->asArray()->all(), 'id', 'name');
    }

    public function getNewNames(): array
    {
        return array_filter(array_map('trim', preg_split('#\s*,\s*#i', $this->textNew)));
    }

    public function attributeLabels()
    {
        return [
            'existing' => 'Теги',
            'name'=>Yii::t('app','Название'),
            'slug'=>Yii::t('app','Транслитерация')
        ];
    }
}