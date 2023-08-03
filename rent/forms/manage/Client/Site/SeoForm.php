<?php

namespace rent\forms\manage\Client\Site;

use rent\entities\Client\Site\Counter;
use rent\entities\Meta;
use yii\base\Model;
use Yii;

class SeoForm extends Model
{
    public $title;
    public $description;
    public $keywords;

    public function __construct(Meta $meta = null, $config = [])
    {
        if ($meta) {
            $this->title = $meta->title;
            $this->description = $meta->description;
            $this->keywords = $meta->keywords;
        }
        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['title'], 'string', 'max' => 255],
            [['description', 'keywords'], 'string'],
        ];
    }

    public function attributeLabels()
    {
       return [
         'title'=>Yii::t('app','Заголовок'),
         'description'=>Yii::t('app','Описание'),
         'keywords'=>Yii::t('app','Ключевые слова')
       ];
    }
}