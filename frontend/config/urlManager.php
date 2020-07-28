<?php
return [
    'class' => 'yii\web\UrlManager',
    'hostInfo' => $params['frontendHostInfo'],
    'baseUrl'=> '',
    'enablePrettyUrl' => true,
    'showScriptName' => false,
    'cache' => false,
    'rules' => [
        '' => 'site/index',
        '<_a:about>' => 'site/<_a>',
        'contact' => 'contact/index',
        'signup' => 'auth/signup/request',
        'signup/<_a:[\w-]+>' => 'auth/signup/<_a>',
        '<_a:login|logout>' => 'auth/auth/<_a>',
//      TODO: почему-то не срабатывается правило выше, пришлось напасать 2 правила ниже:
        'site/login' => 'auth/auth/login',
        'site/logout' => 'auth/auth/logout',

        'catalog' => 'shop/catalog/index',
        ['class' => 'frontend\urls\CategoryUrlRule'],
        'catalog/<id:\d+>' => 'shop/catalog/product',

        'cabinet' => 'cabinet/default/index',
        'cabinet/<_c:[\w\-]+>' => 'cabinet/<_c>/index',
        'cabinet/<_c:[\w\-]+>/<id:\d+>' => 'cabinet/<_c>/view',
        'cabinet/<_c:[\w\-]+>/<_a:[\w-]+>' => 'cabinet/<_c>/<_a>',
        'cabinet/<_c:[\w\-]+>/<id:\d+>/<_a:[\w\-]+>' => 'cabinet/<_c>/<_a>',

//        'catalog' => 'shop/catalog/index',
//        ['class' => 'frontend\urls\CategoryUrlRule'],
//        'catalog/<id:\d+>' => 'shop/catalog/product',

//              TODO: сделать редирект с /catalog на /catalog/
//                'catalog'=>'catalog/',
//        'catalog/<categoryAlias:([\/\w\W$]+\/)><productAlias:([^\/]+)$>'=>'catalog/index',
//        'catalog/<categoryAlias:([\/\w\W$]+\/)>/'=>'catalog/index',
////                'catalog<categoryAlias:[//\w_\/-]+>/'=>'catalog/index',

        '<_c:[\w\-]+>' => '<_c>/index',
        '<_c:[\w\-]+>/<id:\d+>' => '<_c>/view',
        '<_c:[\w\-]+>/<_a:[\w-]+>' => '<_c>/<_a>',
        '<_c:[\w\-]+>/<id:\d+>/<_a:[\w\-]+>' => '<_c>/<_a>',
    ],
];