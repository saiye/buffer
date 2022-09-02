<?php

namespace Engine\Log;

use Engine\Write\ArrayWrite;
use Engine\Write\FileWrite;
use Engine\Contracts\Write\Write;
use Tests\TestCase;
use Engine\Contracts\Log\LogMaster;

class LogManageTest extends TestCase
{

    public function logPath():string
    {
        return dirname(__DIR__.'/../../runtime/log');
    }

    public function testInfo()
    {
        $message = "hello";
        $params  = [
            "name" => "buffer"
        ];
        $writer  = new FileWrite($this->logPath());
        $log     = new LogManage($writer);
        $log->info($message, $params);
        $info     = $writer->getMessage();
        $needInfo = json_encode([
            "message" => $message,
            "params"  => $params
        ]);
        $this->assertEquals($info, $needInfo);
        $this->assertTrue(true);
    }

    public function testDebug()
    {
        $this->assertTrue(true);
    }

    public function testConfig()
    {
    }

    public function testError()
    {
    }
}
