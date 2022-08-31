<?php

declare(strict_types=1);


namespace Engine\Log;

use Engine\Contracts\Log\LogMaster;
use Engine\Contracts\Write\Write;

class LogManage implements LogMaster
{
    const Log_Error   = 1;
    const Log_Warning = 2;
    const Log_Info    = 3;
    const Log_Debug   = 4;

    private Write $write;

    public function config(Write $write)
    {
        $this->write = $write;
    }

    public function info(string $message, array $params = [])
    {
        $this->write->write(json_encode([
            "message" => $message,
            "params"  => $params
        ]), self::Log_Info);
    }

    public function error(string $message, array $params = [])
    {
        $this->write->write(json_encode([
            "message" => $message,
            "params"  => $params
        ]), self::Log_Error);
    }

    public function debug(string $message, array $params = [])
    {
        $this->write->write(json_encode([
            "message" => $message,
            "params"  => $params
        ]), self::Log_Debug);
    }

    public function warning(string $message, array $params = [])
    {
        $this->write->write(json_encode([
            "message" => $message,
            "params"  => $params
        ]), self::Log_Warning);
    }
}