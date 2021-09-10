<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
    // This core.css file contents all plugings css file.
        "css/core.css",
//    // Theme shortcodes/elements style
//        "css/shortcode/shortcodes.css",
//    // Theme main style
//        "style.css",
//    // Responsive css
//        "css/responsive.css",
//    // User style
//        "css/custom.css",
//// для правки less bootstrap
//        'css/type.less',
    ];
    public $cssOptions = [
        'media' => 'screen',
        'rel'=>'preload'
    ];
    public $js = [
        //Modernizr JS
        "js/vendor/modernizr-2.8.3.min.js",
        "js/plugins.js",
        "js/site.js",
        "js/main.js",

    ];
    public $depends = [
        'yii\web\YiiAsset',
//        'yii\bootstrap\BootstrapAsset',
//        'yii\bootstrap\BootstrapPluginAsset',
        'frontend\assets\BootstrapAsset',
        'frontend\assets\ScrollUpAsset',
        'frontend\assets\OwlCarouselAsset',
        'frontend\assets\WaypointsAsset',
        'frontend\assets\OwlCarousel2FilterAsset',
    ];
//    public $jsOptions = ['position' => \yii\web\View::POS_HEAD];
}
