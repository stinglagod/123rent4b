<?php

namespace frontend\assets;

use yii\web\AssetBundle;

class SlickCarouselAsset extends AssetBundle
{
    public $sourcePath = '@bower/slick-carousel/slick';
    public $css = [
        'slick.css',
    ];
    public $js = [
        'slick.min.js',
    ];
    public $cssOptions = [
        'media' => 'screen',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
