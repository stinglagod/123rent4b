<?php
return  [
    'class' => 'yii\web\UrlManager',
    'hostInfo' => $params['backendHostInfo'],
    'enablePrettyUrl' => true,
    'showScriptName' => false,
    'rules' => [
        '<action:index|login|logout|signup|request-password-reset|reset-password|access-denied|php-info>' => 'site/<action>',
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
    ],
];