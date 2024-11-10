<?php

namespace app\commands;

use app\components\events\factories\UserEventFactory;
use app\components\events\UserEventPublisher;
use app\components\processes\ForkedProcessManager;
use Yii;
use yii\base\InvalidConfigException;
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

    /**
     * @throws InvalidConfigException
     */
    public function actionIndex(): void
    {
        $userCount = Yii::$app->params['userCount'];
        $eventCount = Yii::$app->params['eventCount'];

        $userEventFactory = $this->userEventFactory;
        $this->stdout("Start processing...\n");
        $processManager = new ForkedProcessManager($userCount, function (int $userId) use (&$userEventFactory, &$eventCount) {
            $events = $userEventFactory->create($userId, $eventCount);
            $userEventPublisher = Yii::createObject(UserEventPublisher::class);
            foreach ($events as $event) {
                $userEventPublisher->publish($userId, $event);
            }
        });
        $processManager->run();
        $this->stdout("\nProcessing completed.\n");
    }
}