<?php

namespace Tests\App;

use Engine\Log\LogManage;
use Engine\Write\ArrayWrite;
use Engine\Write\FileWrite;
use Engine\App\Application;
use Tests\TestCase;

class ApplicationTest extends TestCase
{

    public function testSingleton()
    {
        $app = new Application();
        $log = new LogManage(new ArrayWrite());
        $app->singleton("log", $log);
        $this->assertTrue($app->make("log") === $log);
    }

    public function testInstance()
    {
        $this->assertTrue(true);
    }

    public function testMake()
    {
        $this->assertTrue(true);
    }
}
