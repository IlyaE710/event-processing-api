<?php

namespace app\components\loggers;

class NullableLogger implements Logger
{

    public function log(array $data): void {}
}