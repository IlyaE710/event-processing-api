<?php

namespace app\components;

use yii\base\InvalidConfigException;

class ForkedProcessManager implements ProcessManager
{
    private int $userCount;
    private $handleUserEventCallback;

    public function __construct(int $userCount, callable $handleUserEventCallback)
    {
        $this->userCount = $userCount;
        $this->handleUserEventCallback = $handleUserEventCallback;
    }

    public function run(): void
    {
        for ($userId = 1; $userId <= $this->userCount; $userId++) {
            $pid = pcntl_fork();

            if ($pid == -1) {
                throw new InvalidConfigException("Unable to fork process.");
            }

            if ($pid == 0) {
                call_user_func($this->handleUserEventCallback, $userId); // Вызываем callback
                exit(0);
            }
        }

        for ($userId = 0; $userId < $this->userCount; $userId++) {
            pcntl_wait($status);
        }
    }
}