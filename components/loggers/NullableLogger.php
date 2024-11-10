<?php

declare(strict_types=1);

namespace app\components\loggers;

class NullableLogger implements Logger
{
    public function log(array $data): void {}
}
