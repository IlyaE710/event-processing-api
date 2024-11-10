<?php

namespace app\commands;

use app\components\loggers\FilerLogger;
use app\components\loggers\Logger;
use app\components\loggers\NullableLogger;
use app\components\processes\ForkedProcessManager;
use app\components\processes\UserEventWorker;
use app\components\queues\Queue;
use Yii;
use yii\console\Controller;

class QueueController extends Controller
{
    private Logger $logger;

    public function __construct($id, $module, Logger $logger, $config = [])
    {
        $this->logger = $logger;
        parent::__construct($id, $module, $config);
    }

    public function actionRun(): void
    {
        $userCount = Yii::$app->params['userCount'];
        $logger = $this->logger;

        $processManager = new ForkedProcessManager($userCount, static function (int $userId) use (&$logger) {
            $client = Yii::createObject(Queue::class);
            $userEventWorker = new UserEventWorker($client, $logger, $userId);
            $userEventWorker->run();
        });

        $processManager->run();

        $this->stdout("\nProcessing completed.\n");
    }
}