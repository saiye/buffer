<?php

use App\Library\Application;

define('APP_BASE', dirname(__DIR__));

$app=new Application();

$app->singleton(App\Library\Kernel\HttpKernel::class,App\Http\Kernel::class);

$app->singleton(App\Library\Kernel\ConsoleKernel::class,App\Console\ConsoleKernel::class);

return  $app;

