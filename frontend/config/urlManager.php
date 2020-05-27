<?php
return [
    'class' => 'yii\web\UrlManager',
    'hostInfo' => $params['frontendHostInfo'],
    'enablePrettyUrl' => true,
    'showScriptName' => false,
    'rules' => [
        '<action:index|login|logout|signup|request-password-reset|reset-password|access-denied|php-info|lk>' => 'site/<action>',
//              TODO: сделать редирект с /catalog на /catalog/
//                'catalog'=>'catalog/',
        'catalog/<categoryAlias:([\/\w\W$]+\/)><productAlias:([^\/]+)$>'=>'catalog/index',
        'catalog/<categoryAlias:([\/\w\W$]+\/)>/'=>'catalog/index',
//                'catalog<categoryAlias:[//\w_\/-]+>/'=>'catalog/index',
    ],
];