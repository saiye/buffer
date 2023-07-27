<?php

namespace App\Library\Route;

class Route
{
    private $routes = [];

    private $tmpKey='';
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
        $this->tmpKey = $this->getRouteKey($method, $path);
        $this->routes[$this->tmpKey] = $route;
        return $this;
    }

    public function getRouteKey($method, $path): string
    {
        return $method . '_' . $path;
    }

    public function middleware($middleware)
    {
        $this->routes[$this->tmpKey]['middlewares'][] = $middleware;
        return $this;
    }

    public function match($requestMethod, $uri)
    {
        $k = $this->getRouteKey($requestMethod, $uri);
        if (isset($this->routes[$k])) {
            return $this->routes[$k];
        }
        foreach ($this->routes as $route) {
            if ($route['method'] === $requestMethod && $this->isMatch($route['path'], $uri)) {
                return $route;
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
