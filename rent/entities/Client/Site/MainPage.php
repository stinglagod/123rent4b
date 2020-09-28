<?php
namespace rent\entities\Client\Site;

use yii\helpers\Json;

class MainPage
{
    public $mainSlider;
    public $banner1;
    public $category1;
    public $banner2;
    public $category2;
    public $banner3;
    public $category3;

    public function __construct(string $json=null)
    {
        if ($json) {
            $this->set(Json::decode($json));
        }
    }

    public function set($data) {
        foreach ($data AS $key => $value) {
            $this->{$key} = $value;
        }
    }
}