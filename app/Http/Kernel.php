<?php

namespace App\Http;

use App\Http\Middleware\AuthMiddleware;
use App\Library\Kernel\HttpKernel;

class Kernel extends HttpKernel
{
    protected $middleware = [
        AuthMiddleware::class
    ];

    public function start()
    {
        // TODO: Implement start() method.

        echo 'http';
    }

}
