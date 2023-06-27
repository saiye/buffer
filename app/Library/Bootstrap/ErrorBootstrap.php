<?php

namespace App\Library\Bootstrap;

use App\Library\Application;
use App\Library\Exception\AppError;

class ErrorBootstrap extends BootstrapBase
{
    public function bootstrap(Application $app)
    {
        $handler = $app->make(AppError::class);

        $handler->register();
    }
}
