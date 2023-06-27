<?php

namespace App\Library\Route;

use App\Library\Application;
use App\Library\Pipeline\Pipeline;
use App\Library\Request\SwooleRequest;
use App\Library\Response\SwooleResponse;

class Router
{

    private $router;

    protected $app;
    protected $middleware;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }
    public function handleRequest(SwooleRequest $request, SwooleResponse $response)
    {
        $method = $request->getMethod();
        $path = $request->getUri();
//        $handler = $this->routes[$method][$path] ?? null;
//        if ($handler) {
//            // 处理中间件
//            $response = (new Pipeline($this->app))
//                ->send($request)
//                ->through($this->middleware)
//                ->then($this->dispatchToRouter());
//        } else {
//            $response->setStatusCode(404);
//            $response->write('404 Not Found');
//        }
        $response->write($request->input());
        $response->end();
    }

    public function dispatchToRouter(){
        return function ($request) {
            return $this->router->dispatch($request);
        };
    }
}
