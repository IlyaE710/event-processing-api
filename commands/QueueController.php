<?php

declare(strict_types=1);

namespace app\commands;

use app\components\loggers\Logger;
use app\components\processes\ForkedProcessManager;
use app\components\processes\UserEventWorker;
use app\components\queues\Queue;
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
        $userCount = (int) \Yii::$app->params['userCount'];
        $logger = $this->logger;
        $this->stdout("Start processing...\n");
        // todo добавить mutex по userId для очередей
        $processManager = new ForkedProcessManager(
            $userCount,
            static function (int $userId) use (&$logger): void {
                /** @var Queue $queue */
                $queue = \Yii::createObject(Queue::class);
                $userEventWorker = new UserEventWorker(
                    $queue,
                    $userId,
                    static function (array $eventData) use (&$logger): void {
                        $logger->log($eventData);
                        sleep(1);
                    }
                );
                $userEventWorker->run();
            }
        );

        $processManager->run();

        $this->stdout("\nProcessing completed.\n");
    }
}
