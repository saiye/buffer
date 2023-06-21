<?php

namespace App\Library\Provider;

use App\Library\Application;
use App\Library\Contract\ServiceProvider;
use App\Library\Route\Router;

class RouteServerProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(Router::class, function (Application $app) {
            return new Router($app);
        });
    }
}
