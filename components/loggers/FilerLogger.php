<?php

namespace app\components\loggers;

class FilerLogger implements Logger
{

    public function log(array $data): void
    {
        \Yii::info($data);
    }
}