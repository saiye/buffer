<?php

namespace App\Console;

use App\Library\Bootstrap\ErrorBootstrap;
use App\Library\Kernel\ConsoleKernel as BaseKernel;

class ConsoleKernel extends BaseKernel
{
    protected $bootstrap = [
        ErrorBootstrap::class
    ];
}
