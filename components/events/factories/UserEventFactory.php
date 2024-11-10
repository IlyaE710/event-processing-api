<?php

declare(strict_types=1);

namespace app\components\events\factories;

class UserEventFactory
{
    public function create(int $userId, int $eventCount): \Generator
    {
        for ($eventId = 1; $eventId <= $eventCount; ++$eventId) {
            yield [
                'accountId' => $userId,
                'eventId' => $eventId,
            ];
        }
    }
}
