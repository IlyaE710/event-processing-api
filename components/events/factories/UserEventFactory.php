<?php

namespace app\components\events\factories;

use Generator;

class UserEventFactory
{
    public function create(string $userId, int $eventCount): Generator
    {
        for ($eventId = 1; $eventId <= $eventCount; $eventId++) {
            yield [
                'accountId' => $userId,
                'eventId' => $eventId,
            ];
        }
    }
}