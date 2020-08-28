<?php

namespace frontend\assets;

use yii\web\AssetBundle;

class ScrollupAsset extends AssetBundle
{
    public $sourcePath = '@bower/scrollup/dist';
    public $css = [
    ];
    public $js = [
        'jquery.scrollUp.min.js',
    ];
    public $cssOptions = [
        'media' => 'screen',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
