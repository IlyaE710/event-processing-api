<?php

declare(strict_types=1);

namespace app\components\queues;

interface Queue
{
    /**
     * Добавить элемент в очередь.
     */
    public function push(string $queueName, string $data): void;

    /**
     * Получить элемент из очереди.
     */
    public function pop(string $queueName): mixed;
}
