<?php

namespace App\Library\Route;

use App\Exception\NotFindException;
use App\Library\Application;
use App\Library\Contract\Response;
use App\Library\Request\Request;
class Router
{
    /**
     * @var $route Route::class
     */
    protected $route;

    protected $app;

    public function __construct(Application $app, array $routeFiles)
    {
        $this->app = $app;
        foreach ($routeFiles as $path) {
            require_once $path;
        }
        $this->route = $app->make(Route::class);
    }

    public function dispatch(Request $request): response
    {
        $method = $request->getMethod();
        $uri = $request->uri();
        $routeMap = $this->route->match($method, $uri);
        if ($routeMap !== null) {
            if ($routeMap['callback'] instanceof \Closure) {
                $responseRes = call_user_func($routeMap['callback']);
            } else {
                $responseRes = $this->app->callFunction($routeMap['callback'][0], $routeMap['callback'][1]);
            }
            if ($responseRes instanceof Response) {
                return $responseRes;
            }
            /**
             * @var $response Response
             */
            $response = $this->app->make(Response::class);
            if (is_array($responseRes)) {
                $responseRes = json_encode($responseRes);
                $response->setHeader('content-type','application/json');
            } elseif (!is_string($responseRes)){
                $responseRes = print_r($responseRes, true);
            }
            $response->setContent($responseRes);
            return $response;
        }
        throw new NotFindException('404 Not Found' . $uri);
    }
}