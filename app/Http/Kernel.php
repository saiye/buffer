<?php

namespace App\Http;

use App\Http\Middleware\AuthMiddleware;
use App\Library\Bootstrap\ErrorBootstrap;
use App\Library\Kernel\HttpKernel;

class Kernel extends HttpKernel
{
    protected $bootstrap = [
        ErrorBootstrap::class
    ];
    protected $middleware = [
        AuthMiddleware::class
    ];

    public function bootstrap()
    {
        foreach ($this->bootstrap as $class) {
            (new $class())->bootstrap($this->app);
        }
    }


    public function start()
    {
        $this->bootstrap();

        echo 'http';
    }

}
