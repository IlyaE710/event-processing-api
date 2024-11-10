<?php

namespace app\commands;

use app\components\UserEventWorker;
use Redis;
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
                $client = new Redis();
                $client->connect('redis', 6379);
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