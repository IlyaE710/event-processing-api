<?php

namespace app\components\queues;


class RedisQueue implements Queue
{
    public function __construct(private readonly \Redis $redis)
    {
        $this->redis->connect('redis');
    }

    /**
     * Добавить элемент в очередь.
     */
    public function push(string $queueName, string $data): void
    {
        $this->redis->rpush($queueName, $data);
    }

    /**
     * Получить элемент из очереди.
     */
    public function pop(string $queueName): ?string
    {
        return $this->redis->lpop($queueName);
    }
}