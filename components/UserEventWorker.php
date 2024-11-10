<?php

namespace app\components;

use Redis;

class UserEventWorker
{
    public function __construct(
        private Redis $client,
        private readonly int $userId
    ) {}

    public function run(): void
    {
        while (true) {
            $event = $this->client->lpop('user_events_' . $this->userId);
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
//        echo "Processing event: " . json_encode($eventData);
    }
}