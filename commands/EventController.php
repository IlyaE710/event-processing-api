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
        $userCount = 100;
        $eventCount = 100;
        for ($userId = 1; $userId <= $userCount; $userId++) {
            $events = $this->userEventFactory->create($userId, $eventCount);
            foreach ($events as $event) {
                $this->userEventPublisher->publish($userId, $event);
            }
        }
    }
}