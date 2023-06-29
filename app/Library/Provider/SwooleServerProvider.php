<?php

namespace App\Library\Provider;

use App\Library\Application;
use App\Library\Config\Config;
use App\Library\Contract\ServiceProvider;
use App\Library\HttpSwooleServer;

class SwooleServerProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(HttpSwooleServer::class, function (Application $app) {

            $config = $app->make(Config::class);

            $port = (int)$config->config('server.port', 9505);

            $host = $config->config('server.host', '0.0.0.0');

            $options = $config->config('server.swoole.options', [
                'log_file' => "swoole.log",
            ]);
            return new HttpSwooleServer($app, $host, $port, SWOOLE_PROCESS, SWOOLE_SOCK_TCP, $options);
        });
    }
}
