<?php

namespace app\components;

use Redis;

class UserEventPublisher
{
    public function __construct(
        private Redis $client,
        private readonly string $queuePrefix = 'user_events_'
    ) {}

    public function publish(string $userId, array $event): void
    {
        $queueName = $this->queuePrefix . $userId;
        $this->client->rpush($queueName, json_encode($event));
    }
}