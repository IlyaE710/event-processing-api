<?php

declare(strict_types=1);

namespace app\components\processes;

interface ProcessManager
{
    public function run(): void;
}
