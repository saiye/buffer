<?php

use App\Library\Application;

function env(string $key, string $default)
{
    return Application::getApplication()->make('App\Library\Env')->env($key, $default);
}

function storage_path(string $path): string
{
    return APP_BASE . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . $path;
}
