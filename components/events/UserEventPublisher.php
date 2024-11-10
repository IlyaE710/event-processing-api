<?php

namespace app\components\events;

use app\components\queues\Queue;

class UserEventPublisher
{
    public function __construct(
        private Queue $client,
        private readonly string $queuePrefix = 'user_events_'
    ) {}

    public function publish(string $userId, array $event): void
    {
        $queueName = $this->queuePrefix . $userId;
        $this->client->push($queueName, json_encode($event));
    }
}