<?php

namespace frontend\widgets\Shop;

use yii\base\Widget;
use yii\helpers\Html;

class BannerWidget extends Widget
{
    public $image;
    public $name;
    public $url;

    public function __construct( $config = [])
    {
        parent::__construct($config);
    }

    public function run(): string
    {
        if (empty($this->image))
            return '';
        else
            return $this->render('banner',[
                'image'=>$this->image,
                'name'=>$this->name,
                'url'=>$this->url,
            ]);
    }

}
