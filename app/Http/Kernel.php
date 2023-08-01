<?php

namespace App\Http;

use App\Http\Middleware\AdminAuthMiddleware;
use App\Http\Middleware\UserAuthMiddleware;
use App\Library\Bootstrap\ErrorBootstrap;
use App\Library\Kernel\HttpKernel;
use App\Library\Request\Request;
class Kernel extends HttpKernel
{
    protected $bootstrap = [
        ErrorBootstrap::class
    ];
    protected $middleware = [

    ];

    protected $middlewareGroups = [

    ];
    protected $middlewareAliases = [
        'UserAuth' => UserAuthMiddleware::class,
        'AdminAuth' => AdminAuthMiddleware::class,
    ];

    public function bootstrap()
    {
        foreach ($this->bootstrap as $class) {
            (new $class())->bootstrap($this->app);
        }
    }

    public function start()
    {
        $response = $this->handleRequest(new Request());
        $response->end();
    }



}
