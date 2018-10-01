<?php

$params = require dirname(dirname(__DIR__)) . '/config/params.php';
$db = require dirname(dirname(__DIR__)) . '/config/db.php';

$config = [
    'id' => 'app-api',
    'language' => 'en',
    'basePath' => dirname(dirname(__DIR__)),
    'bootstrap' => [
        'log',
        'api\modules\v1\Bootstrap',
    ],
    'homeUrl' => '/api',
    'aliases' => [
        '@api' => '@app/api',
        '@modules' => '@app/modules',
    ],
    'modules' => [
        'v1' => [
            'class' => 'api\modules\v1\Module'   // here is our v1 modules
        ],
    ],
    'components' => [
        'request' => [
            'cookieValidationKey' => '10-LLBFJCzkPFn6osY6xPSsDZDfJ89joG',
            'baseUrl' => '/api',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],
        'user' => [
            'identityClass' => 'api\modules\v1\models\User',
            'enableSession' => false,
            'enableAutoLogin' => false,
            'loginUrl' => null,
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
        'db' => $db,
        'authManager' => [
            'class' => 'yii\rbac\PhpManager',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => [],
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
    ],
    'params' => $params,
];
return $config;
