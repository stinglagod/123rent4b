<?php

namespace rent\entities\Shop;

use rent\entities\Client\Site;
use rent\entities\behaviors\MetaBehavior;
use rent\entities\Meta;
use yii\db\ActiveRecord;
use yii\db\ActiveQuery;
use rent\entities\behaviors\ClientBehavior;
use Yii;

/**
 * @property integer $id
 * @property string $name
 * @property string $slug
 * @property Meta $meta
 * @property integer $site_id
 * @property integer $client_id
 *
 * @property \rent\entities\Client\Site $site
 *
 * @method ActiveQuery find(bool $all)
 */
class Brand extends ActiveRecord
{
    public $meta;

    public static function create($name, $slug, Meta $meta): self
    {
        $brand = new static();
        $brand->name = $name;
        $brand->slug = $slug;
        $brand->meta = $meta;
        return $brand;
    }

    public function edit($name, $slug, Meta $meta): void
    {
        $this->name = $name;
        $this->slug = $slug;
        $this->meta = $meta;
    }

    ##########################

    public static function tableName(): string
    {
        return '{{%shop_brands}}';
    }

    public function behaviors(): array
    {
        return [
            ClientBehavior::class,
            MetaBehavior::class,
        ];
    }

    public function getSite(): ActiveQuery
    {
        return $this->hasOne(Site::class, ['id' => 'site_id']);
    }

    public function attributeLabels()
    {
        return[
            'id' => 'ID',
            'name' => 'Название',
            'slug' => 'Транслитерация',
            'title'=>'Заголовок',
            'description'=>'Описание',
            'keywords'=>'Ключевые слова',
];
    }
}