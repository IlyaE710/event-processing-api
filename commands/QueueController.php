<?php

namespace app\commands;

use app\components\UserEventWorker;
use yii\console\Controller;

class QueueController extends Controller
{
    public function actionRun(): void
    {
        $client = new \Redis();
        $client->connect('redis', 6379);
        $userEventWorker = new UserEventWorker($client, 1);
        $userEventWorker->run();
    }
}