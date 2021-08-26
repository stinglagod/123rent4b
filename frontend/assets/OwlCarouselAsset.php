<?php

namespace frontend\assets;

use yii\web\AssetBundle;

class OwlCarouselAsset extends AssetBundle
{
    public $sourcePath = '@bower/owl.carousel/dist';
    public $css = [
        YII_ENV_DEV ? 'assets/owl.carousel.css':'assets/owl.carousel.min.css',
    ];
    public $js = [
        YII_ENV_DEV ? 'owl.carousel.js' : 'owl.carousel.min.js'
//        'plugin/owlCarousel.js'
    ];
    public $cssOptions = [
        'media' => 'screen',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
