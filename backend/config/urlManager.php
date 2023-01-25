<?php
/** @var array $params */
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
        '<_a:login|logout|signup>' => 'auth/auth/<_a>',
        'term' => 'site/term',

        'shop/<layout:order>/catalog/<id:\d+>' => 'shop/catalog/product',
        'shop/catalog/<id:\d+>' => 'shop/catalog/product',

        'shop/catalog' => 'shop/catalog/index',
        'shop/<layout:order>/catalog' => 'shop/catalog/index',

        ['class' => 'backend\urls\CategoryUrlRule'],

        '<_c:[\w\-]+>' => '<_c>/index',
        '<_c:[\w\-]+>/<id:\d+>' => '<_c>/view',
        '<_c:[\w\-]+>/<_a:[\w-]+>' => '<_c>/<_a>',
        '<_c:[\w\-]+>/<id:\d+>/<_a:[\w\-]+>' => '<_c>/<_a>',


    ],
];