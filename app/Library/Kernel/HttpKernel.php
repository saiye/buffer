<?php

namespace App\Library\Kernel;

use App\Library\Application;
use App\Library\Contract\Kernel;
use App\Library\Contract\Request as BaseRequest;
use App\Library\Contract\Response;
use App\Library\Pipeline\Pipeline;
use App\Library\Request\Request;
use App\Library\Route\Router;

abstract class HttpKernel implements Kernel
{
    protected $app;
    /**
     * @var $router Router
     */
    protected $router;

    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->router = $this->app->make(Router::class);
        $this->bootstrap();
    }

    public function handleRequest(BaseRequest  $request): Response
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
            return $this->router->dispatch($request);
        };
    }


}
