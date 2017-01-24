<?php

Yii::setAlias('@tests', dirname(__DIR__) . '/tests');

$params = require(__DIR__ . '/params.php');
$db = require(__DIR__ . '/db.php');
$i18n = require(__DIR__ . '/i18n.php');

return [
    'id' => 'basic-console',
    'basePath' => dirname(__DIR__),
    'language' => 'ru-RU',
    'sourceLanguage' => 'en-US',
    'bootstrap' => ['log', 'gii', 'debug'],
    'controllerNamespace' => 'app\commands',
    'modules' => [
        'gii' => 'yii\gii\Module',
        'debug' => 'yii\debug\Module'
    ],
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'i18n' => $i18n,
        'db' => $db,
        'notificator' => [
            'class' => 'app\components\Notificator',
        ]
    ],
    'params' => $params,
];
