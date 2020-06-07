<?php

namespace rent\entities\Shop;

use yii\db\ActiveRecord;
use rent\entities\Client\Client;
use yii\db\ActiveQuery;
use rent\entities\behaviors\ClientBehavior;

/**
 * @property integer $id
 * @property string $name
 * @property string $slug
 * @property integer $client_id
 *
 * @property \rent\entities\Client\Client $client
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

    public function getClient() :ActiveQuery
    {
        return $this->hasOne(Client::class, ['id' => 'client_id']);
    }
}