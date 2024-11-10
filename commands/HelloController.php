<?php

declare(strict_types=1);

namespace app\commands;

use yii\console\Controller;

class HelloController extends Controller
{
    public function actionIndex(): void
    {
        $this->stdout("Hello World!\n");
    }
}
