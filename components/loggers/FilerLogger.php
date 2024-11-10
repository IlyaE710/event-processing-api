<?php

declare(strict_types=1);

namespace app\components\loggers;

class FilerLogger implements Logger
{
    public function log(array $data): void
    {
        \Yii::info($data);
    }
}
