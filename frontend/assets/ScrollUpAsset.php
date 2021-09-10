<?php

namespace frontend\assets;

use yii\web\AssetBundle;

class ScrollUpAsset extends AssetBundle
{
    public $sourcePath = '@bower/scrollup/dist';
    public $css = [
    ];
    public $js = [
        YII_ENV_DEV ? 'jquery.scrollUp.js' :'jquery.scrollUp.min.js',
    ];
    public $cssOptions = [
        'media' => 'screen',
//        'rel'=>'preload'
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
