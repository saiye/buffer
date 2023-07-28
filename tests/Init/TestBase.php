<?php

namespace Tests\Init;


use App\Library\Application;
use PHPUnit\Framework\TestCase as BaseTestCase;

class TestBase extends BaseTestCase
{
    protected static $app;

    protected function setUp(): void
    {
        if (self::$app==null) {
            self::$app = $this->createApplication();
        }
    }
    private function createApplication(): Application
    {
        return require_once __DIR__ . '/../../bootstrap/app.php';
    }
}
