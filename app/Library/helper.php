<?php

use App\Library\Env;

function env(string $key, string $default=null)
{
    static $env;
    if (!$env) {
        $env = new Env(dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . '.env');
    }
    return $env->env($key, $default);
}

function storage_path(string $path): string
{
    return APP_BASE . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . $path;
}
