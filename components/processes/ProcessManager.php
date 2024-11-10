<?php

namespace app\components\processes;

interface ProcessManager
{
    public function run(): void;
}