<?php

namespace app\commands;

use app\components\events\factories\UserEventFactory;
use app\components\events\UserEventPublisher;
use app\components\loggers\FilerLogger;
use app\components\processes\ForkedProcessManager;
use app\components\queues\Queue;
use Yii;
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
        $userCount = Yii::$app->params['userCount'];
        $eventCount = Yii::$app->params['eventCount'];

        $totalEvents = $userCount * $eventCount;

        $this->stdout("Start processing...\n");
        $processManager = new ForkedProcessManager($userCount, function (int $userId) use ($eventCount, $totalEvents) {
            $events = $this->userEventFactory->create($userId, $eventCount);
            $client = Yii::createObject(Queue::class);
            $userEventPublisher = new UserEventPublisher($client);
            foreach ($events as $index => $event) {
                $userEventPublisher->publish($userId, $event);

                $currentProgress = (($userId - 1) * $eventCount + ($index + 1)) / $totalEvents * 100;
                $this->stdout(sprintf("\rProgress: %.2f%%", $currentProgress));
            }
        });

        $processManager->run();
    }
}