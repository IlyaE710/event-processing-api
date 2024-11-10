<?php

namespace app\components\processes;


use app\components\loggers\Logger;
use app\components\queues\Queue;

class UserEventWorker
{
    public function __construct(
        private Queue $client,
        private Logger $logger,
        private readonly int $userId,
    ) {}

    public function run(): void
    {
        while (true) {
            $event = $this->client->pop('user_events_' . $this->userId);
            if ($event) {
                $eventData = json_decode($event, true);
                $this->processEvent($eventData);
            } else {
                sleep(1);
            }
        }
    }

    protected function processEvent(array $eventData): void
    {
        $this->logger->log($eventData);
    }
}