<?php

namespace Tests;


use App\Library\Application;
use PHPUnit\Framework\TestCase;

class TestBase extends TestCase
{
    protected $app;

    protected function setUp(): void
    {
        if ($this->app==null) {
            $this->app = $this->createApplication();
        }
    }

    private function createApplication(): Application
    {
        return require_once __DIR__ . '/../bootstrap/app.php';
    }
}
