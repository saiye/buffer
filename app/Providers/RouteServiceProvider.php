<?php

namespace App\Providers;

use App\Library\Application;
use App\Library\Contract\ServiceProvider;
use App\Library\Route\Route;
use App\Library\Route\Router;

class RouteServiceProvider extends ServiceProvider
{

    public function boot()
    {

    }

    public function register()
    {
        $this->app->singleton(Route::class);
        
        $this->app->singleton(Router::class, function (Application $app) {
            $routeFiles = [
                 $app->getPath('path.routes') . DIRECTORY_SEPARATOR . 'api.php',
                 $app->getPath('path.routes') . DIRECTORY_SEPARATOR . 'admin.php',
            ];
            return (new Router($app,$routeFiles));
        });
    }
}
