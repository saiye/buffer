<?php

namespace App\Library\Route;

use App\Exception\NotFindException;
use App\Library\Application;
use App\Library\Contract\Response;
use App\Library\Request\Request;

class Router
{
    protected $group;

    protected $app;

    public function __construct(Application $app, array $group)
    {
        $this->app = $app;
        foreach ($group as $prefix => $path) {
            $this->group[$prefix] = require_once $path;
        }
    }

    public function dispatch(Request $request): response
    {
        $method = $request->getMethod();
        $uri = $request->uri();
        $prefix = $this->getUriPrefix($uri);
        if (!empty($prefix)) {
            $prefix = array_key_first($this->group);
        }
        if ($prefix) {
            /**
             * @var  $route Route
             */
            $route = $this->group[$prefix] ?? null;
            if ($route) {
                $responseRes = $route->match($method, $uri);
                if ($responseRes instanceof Response) {
                    return $responseRes;
                } elseif (is_array($responseRes)) {
                    $responseRes = json_encode($responseRes);
                } else {
                    $responseRes = print_r($responseRes, true);
                }
                /**
                 * @var $response Response
                 */
                $response = $this->app->make(Response::class);
                $response->setContent($responseRes);
                return $response;
            }
        }
        throw new NotFindException('404 Not Found' . $uri);
    }

    public function getUriPrefix($uri): string
    {
        if ($uri == '/') {
            return '';
        }
        $info = explode('/', $uri);
        return $info[1] ?? '';
    }

}
