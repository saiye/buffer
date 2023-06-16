<?php

namespace Tests;


use App\Server\AppContainer;
use PHPUnit\Framework\TestCase;

class TestBase extends TestCase
{
    protected $app;

    protected function setUp(): void
    {
        $this->app = $this->createApplication();
    }
    private function createApplication(): AppContainer
    {
        return require __DIR__ . '/../bootstrap/app.php';
    }
}
