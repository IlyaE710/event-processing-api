<?php

declare(strict_types=1);

namespace app\components\loggers;

interface Logger
{
    public function log(array $data): void;
}
