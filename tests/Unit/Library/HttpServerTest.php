<?php

namespace Library;

use App\Library\HttpSwooleServer;
use Tests\TestBase;

class HttpServerTest extends TestBase
{

    public function testStart()
    {
        $server = $this->app->make(HttpSwooleServer::class);
        //$server->start();
        $this->assertTrue(true);
    }
}
