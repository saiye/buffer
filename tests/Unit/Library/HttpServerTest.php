<?php

namespace Library;

use App\Library\HttpServer;
use Tests\TestBase;

class HttpServerTest extends TestBase
{

    public function testStart()
    {
        $server = $this->app->make(HttpServer::class);
        $server->start();
        $this->assertTrue(true);
    }
}
