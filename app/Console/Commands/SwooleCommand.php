<?php

namespace App\Console\Commands;

use App\Console\BaseCommand;
use App\Library\HttpSwooleServer;


class SwooleCommand extends BaseCommand
{
    public function description(): string
    {
        //start|php artisan swoole -action start
        //stop|php artisan swoole -action stop
        return ' swoole service';
    }

    public function run(): void
    {
        $action = $this->option('action');
        switch ($action) {
            case 'start':
                $this->start();
                break;
            case 'stop':
                $this->stop();
                break;
            default:
                throw new \Exception("not find action:".$action);
        }
    }

    public function start()
    {
        /**
         * @var $server HttpSwooleServer
         */
        $server = $this->app->make(HttpSwooleServer::class);
        $server->start();
    }

    public function stop()
    {
        echo 'stop';
    }

}
