<?php

namespace rent\forms\manage\Shop;

use rent\entities\Shop\Tag;
use rent\validators\SlugValidator;
use yii\base\Model;
use Yii;

class TagForm extends Model
{
    public $name;
    public $slug;

    private $_tag;

    public function __construct(Tag $tag = null, $config = [])
    {
        if ($tag) {
            $this->name = $tag->name;
            $this->slug = $tag->slug;
            $this->_tag = $tag;
        }
        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['name', 'slug'], 'required'],
            [['name', 'slug'], 'string', 'max' => 255],
            ['slug', SlugValidator::class],
            [['name', 'slug'], 'unique', 'targetClass' => Tag::class, 'filter' => $this->_tag ? ['<>', 'id', $this->_tag->id] : null]
        ];
    }
    public function attributeLabels()
    {
    return
        [
        'name' => 'Название',
        'slug' => 'Транслитерация',
        ];
    }
}
