<?php

namespace rent\entities\Shop;

use yii\db\ActiveRecord;
use rent\entities\Client\Site;
use yii\db\ActiveQuery;
use rent\entities\behaviors\ClientBehavior;
use Yii;

/**
 * @property integer $id
 * @property string $name
 * @property string $slug
 * @property integer $site_id
 *
 * @property \rent\entities\Client\Site $site
 */
class Tag extends ActiveRecord
{
    public static function create($name, $slug): self
    {
        $tag = new static();
        $tag->name = $name;
        $tag->slug = $slug;
        return $tag;
    }

    public function edit($name, $slug): void
    {
        $this->name = $name;
        $this->slug = $slug;
    }

    public static function tableName(): string
    {
        return '{{%shop_tags}}';
    }

    public function behaviors(): array
    {
        return [
            ClientBehavior::class,
        ];
    }

    public function getSite() :ActiveQuery
    {
        return $this->hasOne(Site::class, ['id' => 'site_id']);
    }
    public static function find()
    {
        return parent::find()->where(['site_id' => Yii::$app->settings->site->id]);
    }
}