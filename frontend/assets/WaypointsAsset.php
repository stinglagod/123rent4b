<?php

namespace frontend\assets;

use yii\web\AssetBundle;

class WaypointsAsset extends AssetBundle
{
    public $sourcePath = '@bower/waypoints/lib';
    public $css = [
    ];
    public $js = [
        YII_ENV_DEV ? 'jquery.waypoints.js' :'jquery.waypoints.min.js',
    ];
    public $cssOptions = [
        'media' => 'screen',
        'rel'=>'preload'
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
