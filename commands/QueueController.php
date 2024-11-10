<?php

namespace app\commands;

use app\components\loggers\Logger;
use app\components\processes\ForkedProcessManager;
use app\components\processes\UserEventWorker;
use app\components\queues\Queue;
use Yii;
use yii\base\InvalidConfigException;
use yii\console\Controller;

class QueueController extends Controller
{
    private Logger $logger;

    public function __construct($id, $module, Logger $logger, $config = [])
    {
        $this->logger = $logger;
        parent::__construct($id, $module, $config);
    }

    /**
     * @throws InvalidConfigException
     */
    public function actionRun(): void
    {
        $userCount = Yii::$app->params['userCount'];
        $logger = $this->logger;
        $this->stdout("Start processing...\n");
        $processManager = new ForkedProcessManager(
            $userCount,
            static function (int $userId) use (&$logger) {
                $userEventWorker = new UserEventWorker(
                    Yii::createObject(Queue::class),
                    $userId,
                    static function (array $eventData) use (&$logger) {
                        $logger->log($eventData);
                    });
            $userEventWorker->run();
        });

        $processManager->run();

        $this->stdout("\nProcessing completed.\n");
    }
}