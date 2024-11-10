<?php

namespace app\commands;

use app\components\loggers\FilerLogger;
use app\components\loggers\NullableLogger;
use app\components\processes\ForkedProcessManager;
use app\components\processes\UserEventWorker;
use app\components\queues\Queue;
use yii\console\Controller;

class QueueController extends Controller
{
    public function actionRun(): void
    {
        $userCount = 1000;
        $logger = new FilerLogger();

        $processManager = new ForkedProcessManager($userCount, static function (int $userId) use (&$logger) {
            $client = \Yii::createObject(Queue::class);
            $userEventWorker = new UserEventWorker($client, $logger, $userId);
            $userEventWorker->run();
        });

        $processManager->run();

        $this->stdout("\nProcessing completed.\n");
    }
}