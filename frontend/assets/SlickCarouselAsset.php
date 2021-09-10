<?php

namespace frontend\assets;

use yii\web\AssetBundle;

class SlickCarouselAsset extends AssetBundle
{
    public $sourcePath = '@bower/slick-carousel/slick';
    public $css = [
        YII_ENV_DEV ? 'slick.css': 'slick.min.css',
    ];
    public $js = [
        YII_ENV_DEV ? 'slick.js' :'slick.min.js',
    ];
    public $cssOptions = [
        'media' => 'screen',
        'rel'=>'preload'
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
