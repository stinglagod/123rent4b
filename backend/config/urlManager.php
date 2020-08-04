<?php
return  [
    'class' => 'yii\web\UrlManager',
    'hostInfo' => $params['backendHostInfo'],
    'baseUrl'=> '/admin/',
    'enablePrettyUrl' => true,
    'showScriptName' => false,
    'cache' => false,
    'rules' => [
        '' => 'site/index',
//        '<action:index|signup|request-password-reset|reset-password|access-denied|php-info>' => 'site/<action>',
        '<_a:login|logout>' => 'auth/<_a>',
//      TODO: почему-то не срабатывается правило выше, пришлось напасать 2 правила ниже:
        'site/login' => 'auth/login',
        'site/logout' => 'auth/logout',

        'catalog' => 'shop/catalog/index',
        ['class' => 'backend\urls\CategoryUrlRule'],
        'shop/catalog/<id:\d+>' => 'shop/catalog/product',

        'category/test'=>'category/test',
        'category/move'=>'category/move',
        'category/add-ajax'=>'category/add-ajax',
        'category/del-ajax'=>'category/del-ajax',
        'category/view-ajax'=>'category/view-ajax',
        'category/tree'=>'category/tree',
        'category/index'=>'category/index',
        'category/update-ajax'=>'category/update-ajax',
        'category/upload'=>'category/upload',
        'category<alias:[//\w_\/-]+>/<product_id:[\d]+>'=>'category/index',
        'category<alias:[//\w_\/-]+>/'=>'category/index',

        '<_c:[\w\-]+>' => '<_c>/index',
        '<_c:[\w\-]+>/<id:\d+>' => '<_c>/view',
        '<_c:[\w\-]+>/<_a:[\w-]+>' => '<_c>/<_a>',
        '<_c:[\w\-]+>/<id:\d+>/<_a:[\w\-]+>' => '<_c>/<_a>',
    ],
];