<?php

namespace rent\forms\manage\Shop\Product;

use rent\entities\Shop\Product\Product;
use rent\entities\Shop\Tag;
use yii\base\Model;
use yii\helpers\ArrayHelper;

/**
 * @property integer $date_begin
 * @property integer $date_end
 * @property integer $qty
 * @property integer $product_id
 * @property integer $type_id
 * @property integer $depend_id
 */
class MovementForm extends Model
{
    public $date_begin;
    public $date_end;
    public $qty;
    public $product_id;
    public $type_id;
    public $depend_id;

    public function __construct(Product $product = null, $config = [])
    {
        if ($product) {
            $this->product_id=$product->id;
        }
        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['date_begin','date_end','product_id','type_id','depend_id'], 'integer'],
            [['date_begin','qty','product_id','type_id'],'required'],
            [['qty'],'integer','min' => 0],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::class, 'targetAttribute' => ['product_id' => 'id']],
            //TODO: правило бы, при определенных type_id, нужно depend_id и(или) date_end
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
            'date_begin' => 'Дата начала',
            'date_end' => 'Дата Конца',
            'qty' => 'Количество',
            'product_id' => 'Продукт',
            'type_id' => 'Тип движения',
            'depend_id' => 'Зависит от',
        ];
    }
}