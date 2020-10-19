<?php
return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\MemCache',
            'useMemcached' => true
        ],
//        'cache' => [
//            'class' => 'yii\caching\FileCache',
//            'cachePath' => '@common/runtime/cache',
//        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
            'itemTable'       => 'auth_items',
            'itemChildTable'  => 'auth_item_children',
            'assignmentTable' => 'auth_assignments',
            'ruleTable'       => 'auth_rules',
            'defaultRoles'    => ['user'],// роль которая назначается всем пользователям по умолчанию
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'ssl://smtp.yandex.com',
                'username' => 'robot@vil.su',
                'password' => 'hFV{81hZZfSZ',
                'port' => '465',
//                'encryption' => 'tls',
            ],
//            'useFileTransport' => true,
            'messageConfig' => [
                'from' => ['robot@vil.su' => 'Rent4b']
            ],
        ],
    ],
];
