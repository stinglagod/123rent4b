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
//        'css/site.css',
    // Bootstrap fremwork main css
        "css/bootstrap.min.css",
    // Owl Carousel main css
        "css/owl.carousel.min.css",
        "css/owl.theme.default.min.css",
    // This core.css file contents all plugings css file.
        "css/core.css",
    // Theme shortcodes/elements style
        "css/shortcode/shortcodes.css",
    // Theme main style
        "style.css",
    // Responsive css
        "css/responsive.css",
    // User style
        "css/custom.css",
// для правки less bootstrap
        'css/type.less',
    ];
    public $js = [
        //Modernizr JS
        "js/vendor/modernizr-2.8.3.min.js",
//        '@bower/waypoints/lib/jquery.waypoints.min.js',
        "js/plugins.js",
        "js/site.js",
        "js/main.js"
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapPluginAsset',
        'frontend\assets\ScrollUpAsset',
        'frontend\assets\OwlCarouselAsset',
        'frontend\assets\WaypointsAsset',

    ];
//    public $jsOptions = ['position' => \yii\web\View::POS_HEAD];
}
