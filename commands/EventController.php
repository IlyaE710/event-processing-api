<?php

namespace app\commands;

use app\components\factories\UserEventFactory;
use app\components\Queue;
use app\components\UserEventPublisher;
use Redis;
use yii\console\Controller;

class EventController extends Controller
{
    private UserEventFactory $userEventFactory;

    public function __construct(
        $id,
        $module,
        UserEventFactory $userEventFactory,
        $config = []
    )
    {
        $this->userEventFactory = $userEventFactory;
        parent::__construct($id, $module, $config);
    }

    public function actionIndex(): void
    {
        $userCount = 1000;
        $eventCount = 1000;

        $totalEvents = $userCount * $eventCount;

        $this->stdout("Start processing...\n");

        for ($userId = 1; $userId <= $userCount; $userId++) {
            $pid = pcntl_fork();

            if ($pid == -1) {
                $this->stderr("Unable to fork process for user $userId.\n");
                continue;
            } elseif ($pid == 0) {
                $events = $this->userEventFactory->create($userId, $eventCount);
                $client = \Yii::createObject(Queue::class);
                $userEventPublisher = new UserEventPublisher($client);
                foreach ($events as $index => $event) {
                    $userEventPublisher->publish($userId, $event);

                    $currentProgress = (($userId - 1) * $eventCount + ($index + 1)) / $totalEvents * 100;
                    $this->stdout(sprintf("\rProgress: %.2f%%", $currentProgress));
                }
                exit;
            }
        }

        while (pcntl_waitpid(0, $status) != -1) {
        }

        $this->stdout("\nProcessing completed.\n");
    }
}