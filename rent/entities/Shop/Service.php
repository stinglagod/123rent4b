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
 * @property float $percent
 * @property boolean $is_depend
 * @property float $defaultCost
 * @property integer $site_id
 *
 * @property \rent\entities\Client\Site $site
 */
class Service extends ActiveRecord
{
    public static function create(string $name,  $percent, $is_depend, $defaultCost): self
    {
        $service = new static();
        $service->name = $name;
        $service->percent = $percent;
        $service->is_depend = $is_depend;
        $service->defaultCost = $defaultCost;
        return $service;
    }

    public function edit(string $name, float $percent,bool $is_depend,float $defaultCost): void
    {
        $this->name = $name;
        $this->percent = $percent;
        $this->is_depend = $is_depend;
        $this->defaultCost = $defaultCost;
    }

    public static function tableName(): string
    {
        return '{{%shop_services}}';
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