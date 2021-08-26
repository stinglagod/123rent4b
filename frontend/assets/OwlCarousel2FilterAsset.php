<?php

namespace frontend\assets;

use yii\web\AssetBundle;

class OwlCarousel2FilterAsset extends AssetBundle
{
    public $sourcePath = '@npm/owlcarousel2-filter/dist';
    public $css = [
//        'theme.css',
    ];
    public $js = [
        'owlcarousel2-filter.min.js',
    ];
    public $cssOptions = [
        'media' => 'screen',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
        'frontend\assets\OwlCarouselAsset',
    ];
}
