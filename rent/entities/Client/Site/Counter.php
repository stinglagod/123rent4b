<?php

namespace rent\entities\Client\Site;


use rent\entities\abstracts\JsonAbstract;
use rent\forms\manage\Client\Site\CounterForm;
use yii\helpers\Json;

class Counter extends JsonAbstract
{
    public $google_tag;
    public $google_counter;
    public $yandex_counter;
    public $facebook_pixel;
    public $yandex_webmaster;

    public function __construct(string $json=null, CounterForm $counterForm=null)
    {
        if ($json) {
            $this->set(Json::decode($json));
        } elseif ($counterForm) {
            $this->google_tag=$counterForm->google_tag;
            $this->google_counter=$counterForm->google_counter;
            $this->yandex_counter=$counterForm->yandex_counter;
            $this->facebook_pixel=$counterForm->facebook_pixel;
            $this->yandex_webmaster=$counterForm->yandex_webmaster;
        }
    }
}