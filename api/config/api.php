<?php

$params = require dirname(dirname(__DIR__)) . '/config/params.php';
$db = require dirname(dirname(__DIR__)) . '/config/db.php';

$config = [
    'id' => 'app-api',
    'language' => 'en',
    'basePath' => dirname(dirname(__DIR__)),
    'bootstrap' => ['log'],
    'homeUrl' => '/api',
    'aliases' => [
        '@api' => '@app/api',
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
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => [
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => [
                        'v1/user'
                    ],
                    'except' => ['delete'],
                    'pluralize' => true,
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => [
                        'v1/message'
                    ],
                    'pluralize' => false,
                ],
            ],
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
    ],
    'params' => $params,
];
return $config;
