<?php

namespace App\Library\Kernel;

use App\Library\Application;
use App\Library\Contract\Kernel;

abstract class HttpKernel implements Kernel
{
    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }
}
