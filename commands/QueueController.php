<?php

namespace app\commands;

use app\components\ForkedProcessManager;
use app\components\Queue;
use app\components\UserEventWorker;
use yii\console\Controller;

class QueueController extends Controller
{
    public function actionRun(): void
    {
        $userCount = 1000;

        $processManager = new ForkedProcessManager($userCount, static function (int $userId) {
            $client = \Yii::createObject(Queue::class);
            $userEventWorker = new UserEventWorker($client, $userId);
            $userEventWorker->run();
        });

        $processManager->run();

        $this->stdout("\nProcessing completed.\n");
    }
}