<?php

namespace app\commands;

use app\components\factories\UserEventFactory;
use app\components\UserEventPublisher;
use yii\console\Controller;

class EventController extends Controller
{
    private UserEventFactory $userEventFactory;
    private UserEventPublisher $userEventPublisher;

    public function __construct(
        $id,
        $module,
        UserEventFactory $userEventFactory,
        UserEventPublisher $userEventPublisher,
        $config = []
    )
    {
        $this->userEventFactory = $userEventFactory;
        $this->userEventPublisher = $userEventPublisher;
        parent::__construct($id, $module, $config);
    }

    public function actionIndex(): void
    {
        $userCount = 1000;
        $eventCount = 100;

        $totalEvents = $userCount * $eventCount;

        $this->stdout("Start processing...\n");

        for ($userId = 1; $userId <= $userCount; $userId++) {
            $events = $this->userEventFactory->create($userId, $eventCount);

            foreach ($events as $index => $event) {
                $this->userEventPublisher->publish($userId, $event);

                $currentProgress = (($userId - 1) * $eventCount + ($index + 1)) / $totalEvents * 100;
                $this->stdout(sprintf("\rProgress: %.2f%%", $currentProgress));
            }
        }

        $this->stdout("\nProcessing completed.\n");
    }
}