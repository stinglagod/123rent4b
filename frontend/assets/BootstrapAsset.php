<?php

namespace frontend\assets;

use yii\web\AssetBundle;

class BootstrapAsset extends AssetBundle
{
    public $sourcePath = '@bower/bootstrap/dist';
    public $css = [
        YII_ENV_DEV ? 'css/bootstrap.css':'css/bootstrap.min.css',
    ];
    public $js = [
        YII_ENV_DEV ? 'js/bootstrap.js' : 'js/bootstrap.min.js'
    ];
    public $cssOptions = [
        'media' => 'screen',
        'rel'=>'preload'
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
