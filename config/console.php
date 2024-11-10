<?php

use app\components\queues\Queue;
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
            Queue::class => static function () {
                $client = new Redis();
                $client->connect(
                    env('REDIS_HOST', 'redis'),
                    (int)env('REDIS_PORT', '6379')
                );
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
