<?php

namespace app\components\loggers;

interface Logger
{
    public function log(array $data): void;
}