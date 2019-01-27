<?php
use \kartik\datecontrol\Module;
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-backend',
    'name' => 'Rent4B.ЛК',
    'basePath' => dirname(__DIR__),
    'homeUrl' => '/admin',
    'language'=>'ru',
    'controllerNamespace' => 'backend\controllers',
    'defaultRoute' => 'site/index',
    'bootstrap' => ['log'],
    'as beforeRequest' => [
        'class' => yii\filters\AccessControl::class,
        'rules' => [
            [
                'allow' => true,
                'controllers' => ['site'],
                'actions' => ['login', 'access-denied','request-password-reset','reset-password'],
                'roles' => ['?','@']
            ],
            [
                'allow' => true,
                'controllers' => ['site'],
                'actions' => ['logout'],
                'roles' => ['@']
            ],
            [
                'allow' => true,
//                'controllers' => ['site','contr-plan-deliv','user','gridview', 'export'],
//                'actions' => ['login', 'access-denied', 'logout','index','create','update','delete','view', 'export-in-job', 'export' ,'download',],
                'roles' => ['admin']
            ],
//            [
//                'allow' => true,
//                'roles' => ['guest']
//            ]
        ],
        'denyCallback' => function () {
//            if( ! Yii::$app->user->isGuest ) {
//                Yii::$app->user->logout();
//            }
            return Yii::$app->response->redirect(['login']);
        },
    ],
    'modules' => [
        'gridview' =>  [
            'class' => '\kartik\grid\Module'
            // enter optional module parameters below - only if you need to
            // use your own export download action or custom translation
            // message source
            // 'downloadAction' => 'gridview/export/download',
            // 'i18n' => []
        ],
        'treemanager' =>  [
            'class' => '\kartik\tree\Module',
            // other module settings, refer detailed documentation
        ],
        'datecontrol' =>  [
            'class' => 'kartik\datecontrol\Module',

            // format settings for displaying each date attribute (ICU format example)
            'displaySettings' => [
//                Module::FORMAT_DATE => 'dd.MM.yyyy',
                Module::FORMAT_DATE => 'dd.MM.yyyy',
                Module::FORMAT_TIME => 'hh:mm:ss a',
                Module::FORMAT_DATETIME => 'dd.MM.yyyy hh:mm:ss a',
            ],

            // format settings for saving each date attribute (PHP format example)
            'saveSettings' => [
                Module::FORMAT_DATE => 'php:Y-m-d H:i:s',
                Module::FORMAT_TIME => 'php:H:i:s',
                Module::FORMAT_DATETIME => 'php:Y-m-d H:i:s',
            ],

//            // set your display timezone
//            'displayTimezone' => 'Europa/Moscow',
//
//            // set your timezone for date saved to db
//            'saveTimezone' => 'UTC',

            // automatically use kartik\widgets for each of the above formats
            'autoWidget' => true,
            // use ajax conversion for processing dates from display format to save format.
            'ajaxConversion' => false,

            // default settings for each widget from kartik\widgets used when autoWidget is true
            'autoWidgetSettings' => [
                kartik\datecontrol\Module::FORMAT_DATE => ['type'=>2, 'pluginOptions'=>[
                    'autoclose'=>true,
                    'todayHighlight' => true,
                    'todayBtn' => true,
                ]], // example
                Module::FORMAT_DATETIME => [], // setup if needed
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
                    ]
                ]
            ]
        ],
    ],
    'components' => [
        'request' => [
            'baseUrl' => '/admin',
            'csrfParam' => '_csrf-backend',
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
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
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '<action:index|login|logout|signup|request-password-reset|reset-password|access-denied>' => 'site/<action>',
                'category/test'=>'category/test',
                'category/move'=>'category/move',
                'category/add-ajax'=>'category/add-ajax',
                'category/del-ajax'=>'category/del-ajax',
                'category/view-ajax'=>'category/view-ajax',
                'category/<category:[\w_\/-]+>/<product_id:[\d]+>'=>'category/index',
                'category/<category:[\w_\/-]+>/'=>'category/index',
            ],
        ],
//        'view' => [
//            'theme' => [
//                'pathMap' => [
//                    '@app/views' => '@vendor/dmstr/yii2-adminlte-asset/example-views/yiisoft/yii2-app'
//                ],
//            ],
//        ],
    ],
    'params' => $params,
];
