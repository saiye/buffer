<?php

namespace App\Library\Provider;

use App\Exception\ExceptionHandler as AppExceptionHandler;
use App\Library\Config\Config;
use App\Library\Contract\ExceptionHandler;
use App\Library\Contract\Logger;
use App\Library\Contract\Response;
use App\Library\Contract\ServiceProvider;
use App\Library\Env;
use App\Library\Exception\AppError;
use App\Library\Log\Log;

class InitServerProvider extends ServiceProvider
{
    public function register()
    {
        $this->pathRegister();

        $this->app->singleton(Env::class, function ($app) {
            return new Env(APP_BASE . DIRECTORY_SEPARATOR . '.env');
        });
        $this->app->singleton(Config::class, function ($app) {
            return new  Config($app);
        });
        $this->app->singleton(Logger::class, function ($app) {
            return new  Log($app);
        });
        $this->app->singleton(ExceptionHandler::class, AppExceptionHandler::class);

        $this->app->singleton(AppError::class);

        $this->app->bind(Response::class, \App\Library\Response\Response::class);
    }

    public function pathRegister()
    {
        $this->app->setPath('path.app', APP_BASE . DIRECTORY_SEPARATOR . 'app');

        $this->app->setPath('path.storage', APP_BASE . DIRECTORY_SEPARATOR . 'storage');

        $this->app->setPath('path.logs', $this->app->getPath('path.storage') . DIRECTORY_SEPARATOR . 'logs');

        $this->app->setPath('path.config', $this->app->getPath('path.app') . DIRECTORY_SEPARATOR . 'config');

        $this->app->setPath('path.config.optimize', $this->app->getPath('path.storage') . DIRECTORY_SEPARATOR . 'config.optimize.php');
    }
}
