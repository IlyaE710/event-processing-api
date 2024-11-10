<?php

namespace app\commands;

use app\components\Queue;
use app\components\UserEventWorker;
use yii\base\InvalidConfigException;
use yii\console\Controller;

class QueueController extends Controller
{
    public function actionRun(): void
    {
        $userCount = 1000;
        for ($userId = 1; $userId <= $userCount; $userId++) {
            $pid = pcntl_fork();

            if ($pid == -1) {
                throw new InvalidConfigException("Unable to fork process.");
            }

            if ($pid == 0) {
                $client = \Yii::createObject(Queue::class);
                $userEventWorker = new UserEventWorker($client, $userId);
                $userEventWorker->run();
                exit(0);
            }
        }

        for ($userId = 0; $userId < $userCount; $userId++) {
            pcntl_wait($status);
        }

        $this->stdout("\nProcessing completed.\n");
    }
}