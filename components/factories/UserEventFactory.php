<?php

namespace app\components\factories;

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