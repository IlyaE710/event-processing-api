<?php

declare(strict_types=1);

namespace app\components\processes;

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
        for ($userId = 1; $userId <= $this->userCount; ++$userId) {
            $pid = pcntl_fork();

            if ($pid === -1) {
                throw new InvalidConfigException('Unable to fork process.');
            }

            if (0 === $pid) {
                \call_user_func($this->handleUserEventCallback, $userId);

                exit(0);
            }
        }

        for ($userId = 0; $userId < $this->userCount; ++$userId) {
            pcntl_wait($status);
        }
    }
}
