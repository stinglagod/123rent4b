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
//        "js/site.js"
        //TODO: все что ниже нужно подключать в конеце страницы
        //TODO: сделать через компоненту yii2
        // jquery latest version
//        "js/vendor/jquery-1.12.0.min.js",
//        // Bootstrap framework js
//        "js/bootstrap.min.js",
//        // All js plugins included in this file.
//        "js/plugins.js",
//        "js/slick.min.js",
//        "js/owl.carousel.min.js",
//        // Waypoints.min.js.
//        "js/waypoints.min.js",
//        // Main js file that contents all jQuery plugins activation.
//        "js/main.js"
    ];
    public $depends = [
//        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',

    ];
    public $jsOptions = ['position' => \yii\web\View::POS_HEAD];
}
