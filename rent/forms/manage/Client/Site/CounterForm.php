<?php

namespace rent\forms\manage\Client\Site;

use rent\entities\Client\Site\Counter;
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

    public function __construct(Counter $counter=null,$config = [])
    {
        if ($counter) {
            $this->google_tag=$counter->google_tag;
            $this->google_counter=$counter->google_counter;
            $this->yandex_counter=$counter->yandex_counter;
            $this->facebook_pixel=$counter->facebook_pixel;
        }
        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['google_tag','google_counter','yandex_counter','facebook_pixel'], 'string'],
        ];
    }

}