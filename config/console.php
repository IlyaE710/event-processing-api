<?php

use app\components\queues\RedisQueue;

$params = require __DIR__ . '/params.php';

$config = [
    'id' => 'event-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'app\commands',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
        '@tests' => '@app/tests',
    ],
    'container' => [
        'definitions' => [
            \app\components\queues\Queue::class => static function () {
                $client = new Redis();
                $client->connect('redis');
                return new RedisQueue($client);
            }
        ],
    ],
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['info'],
                ],
            ],
        ],
    ],
    'params' => $params,
];

return $config;
