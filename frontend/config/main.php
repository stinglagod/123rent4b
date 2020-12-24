<?php
use \kartik\datecontrol\Module;
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-frontend',
    'name' => 'Rent4B',
    'basePath' => dirname(__DIR__),
    'homeUrl' => '/',
    'aliases' => [
        '@staticRoot' => $params['staticPath'],
        '@static'   => $params['staticHostInfo'],
    ],
    'bootstrap' => [
        'log',
        'common\bootstrap\SetUp',
        'frontend\bootstrap\SetUp',
    ],
    'controllerNamespace' => 'frontend\controllers',
//    'defaultRoute' => 'catalog/index',
    'layout' => 'blank',
    'language'=>'ru',
    'modules' => [
        'gridview' =>  [
            'class' => '\kartik\grid\Module'
            // enter optional module parameters below - only if you need to
            // use your own export download action or custom translation
            // message source
            // 'downloadAction' => 'gridview/export/download',
            // 'i18n' => []
        ],
        'datecontrol' =>  [
            'class' => 'kartik\datecontrol\Module',

            // format settings for displaying each date attribute (ICU format example)
            'displaySettings' => [
//                Module::FORMAT_DATE => 'dd.MM.yyyy',
                Module::FORMAT_DATE => 'dd.MM.yyyy',
                Module::FORMAT_TIME => 'hh:mm:ss a',
                Module::FORMAT_DATETIME => 'php:d-m-Y H:i:s',
            ],

            // format settings for saving each date attribute (PHP format example)
            'saveSettings' => [
//                Module::FORMAT_DATE => 'php:Y-m-d H:i:s',
                Module::FORMAT_DATE => 'php:U',
                Module::FORMAT_TIME => 'php:H:i:s',
                Module::FORMAT_DATETIME => 'php:U',
//                Module::FORMAT_DATETIME => 'php:Y-m-d H:i:s',
            ],

//            // set your display timezone
            'displayTimezone' => 'Europe/Moscow',
//            'displayTimezone' => date_default_timezone_get(),
//            'displayTimezone' => 'UTC',
//
//            // set your timezone for date saved to db
//            'saveTimezone' => date_default_timezone_get(),
            'saveTimezone' => 'UTC',

            // automatically use kartik\widgets for each of the above formats
            'autoWidget' => true,
            // use ajax conversion for processing dates from display format to save format.
            'ajaxConversion' => true,

            // default settings for each widget from kartik\widgets used when autoWidget is true
            'autoWidgetSettings' => [
                kartik\datecontrol\Module::FORMAT_DATE => [
                    'type'=>2,
                    'pluginOptions'=>[
                        'autoclose'=>true,
                        'todayHighlight' => true,
                        'todayBtn' => true,
                    ]], // example
                Module::FORMAT_DATETIME => [

                ], // setup if needed
                Module::FORMAT_TIME => [], // setup if needed
            ],

            // custom widget settings that will be used to render the date input instead of kartik\widgets,
            // this will be used when autoWidget is set to false at module or widget level.
            'widgetSettings' => [
                Module::FORMAT_DATE => [
                    'class' => 'kartik\date\DatePicker', // example
                    'options' => [
//                        'dateFormat' => 'php:d-M-Y',
                        'options' => ['class'=>'form-control'],
                        'convertFormat' => true,
                    ]
                ],
                Module::FORMAT_DATETIME => [
                    'class' => 'kartik\date\DatePicker', // example
                    'options' => [
//                        'dateFormat' => 'php:d-M-Y',
                        'options' => ['class'=>'form-control'],
                        'convertFormat' => true,
                    ]
                ],
            ]
        ],
    ],
    'components' => [
        'request' => [
            'baseUrl' => '',
            'csrfParam' => '_csrf-frontend',
            'cookieValidationKey' => $params['cookieValidationKey'],
        ],
        'user' => [
            'identityClass' => 'rent\entities\User\User',
            'enableAutoLogin' => true,
            'identityCookie' => [
                'name' => '_identity',
                'httpOnly' => true,
                'domain' => $params['cookieDomain']
            ],
            'loginUrl' => ['auth/auth/login'],
        ],
        'session' => [
            'name' => '_session',
            'cookieParams' => [
                'domain' => $params['cookieDomain'],
                'httpOnly' => true
            ]
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'frontendUrlManager' => require __DIR__ . '/urlManager.php',
        'backendUrlManager' => require __DIR__ . '/../../backend/config/urlManager.php',
        'urlManager' => function () {
            return Yii::$app->get('frontendUrlManager');
        },
        'assetManager' => [
            'converter' => [
                'class' => 'yii\web\AssetConverter',
                'commands' => [
                    'less' => ['css', 'lessc {from} {to} --no-color'],
                ],
            ],
        ],
        'formatter' => [
            'defaultTimeZone' => 'Europe/Moscow',
            'dateFormat' => 'dd.MM.yyyy',
        ],
        'reCaptcha' => [
            'class' => 'himiklab\yii2\recaptcha\ReCaptchaConfig',
            'siteKeyV3' => YII_ENV_PROD?Yii::$app->settings->site->reCaptcha->google_siteKeyV3:'',
            'secretV3' => YII_ENV_PROD?Yii::$app->settings->site->reCaptcha->google_secretV3:'',
        ],
    ],
    'params' => $params,
];
