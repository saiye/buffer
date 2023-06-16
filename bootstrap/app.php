<?php

use App\Server\AppContainer;

define('APP_BASE', dirname(__DIR__));
// 创建容器实例
$container = new AppContainer();

//path init
$container->setPath('app', APP_BASE . DIRECTORY_SEPARATOR . 'app');

$container->setPath('storage', APP_BASE.DIRECTORY_SEPARATOR . 'storage');

$container->setPath('logs', $container->getPath('storage') . DIRECTORY_SEPARATOR . 'logs');

$container->setPath('config', $container->getPath('app'). DIRECTORY_SEPARATOR . 'config');

$container->setPath('config.optimize.file', $container->getPath('storage') . DIRECTORY_SEPARATOR . 'config.optimize.php');
//class init
$container->singleton('App\Server\Env', function ($app) {
    return new App\Server\Env(APP_BASE . DIRECTORY_SEPARATOR . '.env');
});

$container->singleton('App\Server\HttpSwooleServer', function ($app) {
    return new App\Server\HttpSwooleServer();
});

$container->singleton('App\Server\Config\Config', function ($app) {
    return new App\Server\Config\Config($app);
});

require_once 'helper.php';

return $container;
