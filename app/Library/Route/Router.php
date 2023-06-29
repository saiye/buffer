<?php

namespace App\Library\Route;

use App\Library\Application;
use App\Library\Contract\Request;
use App\Library\Contract\Response;
use App\Library\Pipeline\Pipeline;

class Router
{
    private $router;

    protected $app;
    protected $middleware = [
        \App\Http\Middleware\AuthMiddleware::class
    ];

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function handleRequest(Request $request): Response
    {
        // 处理中间件
        return (new Pipeline($this->app))
            ->send($request)
            ->through($this->middleware)
            ->then($this->dispatchToRouter());
    }

    public function dispatchToRouter()
    {
        /**
         * @var  $request Request
         */
        return function ($request) {
            //分发路由
            // return $this->router->dispatch($request);
            $data = $request->input();
            $res = is_array($data) ? json_encode($data) : $data;
            return (new \App\Library\Response\Response())->setSocket($request)->setContent('get:' . $res);
        };
    }
}
