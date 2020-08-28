<?php

namespace frontend\assets;

use yii\web\AssetBundle;

class WaypointsAsset extends AssetBundle
{
    public $sourcePath = '@bower/waypoints/lib';
    public $css = [
    ];
    public $js = [
        'jquery.waypoints.min.js',
    ];
    public $cssOptions = [
        'media' => 'screen',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
