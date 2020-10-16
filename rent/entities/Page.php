<?php

namespace rent\entities;

use paulzi\nestedsets\NestedSetsBehavior;
use rent\entities\behaviors\ClientBehavior;
use rent\entities\behaviors\MetaBehavior;
use yii\db\ActiveRecord;
use Yii;

/**
 * @property integer $id
 * @property string $title
 * @property string $slug
 * @property string $content
 * @property integer $lft
 * @property integer $rgt
 * @property integer $depth
 * @property Meta $meta
 *
 * @property Page $parent
 * @property Page[] $parents
 * @property Page[] $children
 * @property Page $prev
 * @property Page $next
 * @mixin NestedSetsBehavior
 */
class Page extends ActiveRecord
{
    public $meta;

    public static function create($title, $slug, $content, Meta $meta): self
    {
        $category = new static();
        $category->title = $title;
        $category->slug = $slug;
        $category->title = $title;
        $category->content = $content;
        $category->meta = $meta;
        return $category;
    }
    public static function createRoot(): self
    {
        $category = new static();
        $category->title = '<Корень>';
        $category->slug = 'root';
        $category->content =  null;
        $category->meta = '{}';
        $category->lft=1;
        $category->rgt=2;
        $category->depth=0;
        $category->meta = new Meta('','','');
        return $category;
    }

    public function edit($title, $slug, $content, Meta $meta): void
    {
        $this->title = $title;
        $this->slug = $slug;
        $this->content = $content;
        $this->meta = $meta;
    }

    public function getSeoTitle(): string
    {
        return $this->meta->title ?: $this->title;
    }

    public static function tableName(): string
    {
        return '{{%pages}}';
    }

    public function behaviors(): array
    {
        return [
            MetaBehavior::class,
            NestedSetsBehavior::class,
            ClientBehavior::class,
        ];
    }

    public function transactions(): array
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    public static function find()
    {
        return parent::find()->where(['site_id' => Yii::$app->params['siteId']]);
    }
}