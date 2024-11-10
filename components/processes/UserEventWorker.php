<?php

namespace app\components\processes;


use app\components\loggers\Logger;
use app\components\queues\Queue;

class UserEventWorker
{
    private mixed $eventProcessor;

    public function __construct(
        private Queue $client,
        private readonly int $userId,
        $eventProcessor
    ) {
        $this->eventProcessor = $eventProcessor;
    }

    public function run(): void
    {
        while (true) {
            $event = $this->client->pop('user_events_' . $this->userId);
            if ($event) {
                $eventData = json_decode($event, true);
                call_user_func($this->eventProcessor, $eventData);
            } else {
                sleep(1);
                break;
            }
        }
    }
}