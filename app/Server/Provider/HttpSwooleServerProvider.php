<?php

namespace App\Server\Provider;

use App\Server\AppContainer;
use App\Server\Contract\ServiceProvider;
use App\Server\HttpSwooleServer;

class HttpSwooleServerProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('App\Server\HttpSwooleServer', function (AppContainer $app) {

            $config = $app->make('App\Server\Config');

            $port = $config->config('app.server.port', 9505);

            $host = $config->config('app.server.host', '0.0.0.0');

            $options = $config->config('app.server.swoole.options', [
                'log_file' => "swoole.log",
            ]);
            return new HttpSwooleServer($host, $port, SWOOLE_PROCESS, SWOOLE_SOCK_TCP, $options);
        });
    }
}
