<?php

namespace rent\forms\manage\Client\Site;

use rent\entities\Client\Site\Counter;
use Yii;
use yii\base\Model;

/**
 * @property \rent\forms\manage\Client\Site\MainPage\MainPageCategoryForm[] categories
 **/

class CounterForm extends Model
{
    public $google_tag;
    public $google_counter;
    public $yandex_counter;
    public $facebook_pixel;
    public $yandex_webmaster;

    public function __construct(Counter $counter=null,$config = [])
    {
        if ($counter) {
            $this->google_tag=$counter->google_tag;
            $this->google_counter=$counter->google_counter;
            $this->yandex_counter=$counter->yandex_counter;
            $this->facebook_pixel=$counter->facebook_pixel;
            $this->yandex_webmaster=$counter->yandex_webmaster;
        }
        parent::__construct($config);
    }
    public function attributeLabels()
    {
        return [
            'google_tag' => 'Google tag (перед закрывающие </head>)',
            'google_counter' => 'Google Counter',
            'yandex_counter' => 'Yandex Counter (после <body>)',
            'facebook_pixel' => 'FaceBook Pixel (после <body>)',
            'yandex_webmaster' => 'Яндекс Вебмастере (мета тег в head)',
        ];
    }

    public function rules(): array
    {
        return [
            [['google_tag','google_counter','yandex_counter','facebook_pixel','yandex_webmaster'], 'string'],
        ];
    }

}