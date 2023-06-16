<?php

function env(string $key, string $default = '')
{
    global $container;
    return $container->make("App\Server\Env", [APP_BASE . DIRECTORY_SEPARATOR . '.env'])->env($key, $default);
}

function storage_path(string $path): string
{
    return APP_BASE . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . $path;
}
