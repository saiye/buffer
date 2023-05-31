<?php

use App\Server\AppContainer;

define('APP_BASE', dirname(__DIR__));
// 创建容器实例
$container = new AppContainer();
function env(string $key, string $default) use ($container): mixed
{
    /**
     * @var $container AppContainer
     */
    global $container;

    return $container->make("App\Server\Env", [APP_BASE . DIRECTORY_SEPARATOR . '.env'])->env($key, $default);
}

function storage_path(string $path): string
{
    return APP_BASE . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . $path;
}

return $container;
