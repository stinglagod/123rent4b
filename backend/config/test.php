<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);
return [
    'id' => 'app-backend-tests',
    'components' => [
        'assetManager' => [
            'basePath' => __DIR__ . '/../web/assets',
        ],
        'aliases' => [
            '@staticRoot' => $params['staticPath'],
            '@static'   => $params['staticHostInfo'],
        ],
        'bootstrap' => [
            'log',
            'common\bootstrap\SetUp',
            'backend\bootstrap\SetUp',
        ],
        'urlManager' => [
            'showScriptName' => true,
        ],
    ],
    'params' => $params,
];
