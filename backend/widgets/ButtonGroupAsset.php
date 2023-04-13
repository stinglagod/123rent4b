<?php

namespace backend\widgets;

use yii\web\View;
use yii\web\AssetBundle;

class ButtonGroupAsset extends AssetBundle
{
    public $sourcePath = '@backend/widgets/button-group';
    public $css = [
        'button-group.css'
    ];
    public $js = [
        'button-group.js'
    ];
    public $jsOptions = [
        'position' => View::POS_HEAD
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];
} 