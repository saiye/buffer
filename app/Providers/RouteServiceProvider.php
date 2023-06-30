<?php

namespace App\Providers;

use App\Library\Application;
use App\Library\Contract\ServiceProvider;
use App\Library\Route\Router;

class RouteServiceProvider extends ServiceProvider
{

    public function boot()
    {

    }

    public function register()
    {
        $this->app->singleton(Router::class, function (Application $app) {
            //prefix=>path
            $group = [
                'api' => $app->getPath('path.routes') . DIRECTORY_SEPARATOR . 'api.php',
                'admin' => $app->getPath('path.routes') . DIRECTORY_SEPARATOR . 'admin.php',
            ];
            return (new Router($app,$group));
        });
    }
}
