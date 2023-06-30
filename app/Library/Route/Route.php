<?php

namespace App\Library\Route;
class Route
{
    private $routes = [];

    private $prefix = '';

    public function prefix($prefix)
    {
        $this->prefix = $prefix;

        return $this;
    }

    public function get($path, $callback)
    {
        return $this->addRoute('GET', $path, $callback);
    }

    public function post($path, $callback)
    {
        return $this->addRoute('POST', $path, $callback);
    }

    private function addRoute($method, $path, $callback)
    {
        $route = [
            'method' => $method,
            'path' => $path,
            'callback' => $callback,
            'middlewares' => []
        ];

        $this->routes[] = $route;

        return $this;
    }

    public function middleware($middleware)
    {
        $routeCount = count($this->routes);
        $this->routes[$routeCount - 1]['middlewares'][] = $middleware;
        return $this;
    }

    public function match($requestMethod, $uri)
    {
        foreach ($this->routes as $route) {
            if ($route['method'] === $requestMethod && $this->isMatch($route['path'], $uri)) {
                return [
                    'callback' => $route['callback'],
                    'middlewares' => $route['middlewares']
                ];
            }
        }
        return null;
    }

    private function isMatch($routePath, $requestPath)
    {
        $routePath = preg_replace('/\//', '\/', $routePath);
        $routePath = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '([a-zA-Z0-9_]+)', $routePath);
        $routePath = '/^' . $routePath . '$/';

        return preg_match($routePath, $requestPath);
    }
}
