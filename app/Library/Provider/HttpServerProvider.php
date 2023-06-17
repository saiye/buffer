<?php

namespace App\Library\Provider;

use App\Library\Config\Config;
use App\Library\Container;
use App\Library\Contract\ServiceProvider;
use App\Library\HttpServer;
use App\Library\HttpSwooleServer;

class HttpServerProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(HttpSwooleServer::class, function (Container $app) {

            $config = $app->make(Config::class);

            $port = $config->config('server.port', 9505);

            $host = $config->config('server.host', '0.0.0.0');

            $options = $config->config('server.swoole.options', [
                'log_file' => "swoole.log",
            ]);
            return new HttpSwooleServer($host, $port, SWOOLE_PROCESS, SWOOLE_SOCK_TCP, $options);
        });

        $this->app->singleton(HttpServer::class, function (Container $app) {

            $config = $app->make(Config::class);

            $port = $config->config('server.port', 9505);

            $host = $config->config('server.host', '0.0.0.0');

            return new HttpServer($host, $port);
        });
    }
}
