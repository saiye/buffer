<?php

namespace Server\Config;

use App\Server\Config\Config;
use Tests\TestBase;
class ConfigTest extends TestBase
{

    public function testGet()
    {
       $config= $this->app->make('App\Server\Config\Config');

       $this->assertTrue($config->get('app.name')=='buffer');
    }
}
