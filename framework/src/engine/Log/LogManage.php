<?php

declare(strict_types=1);


namespace Engine\Log;

use Engine\Contracts\Log\LogMaster;
use Engine\Contracts\Write\Write;

class LogManage implements LogMaster
{

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
        ]), "info");
    }

    public function error(string $message, array $params = [])
    {
        $this->write->write(json_encode([
            "message" => $message,
            "params"  => $params
        ]), "error");
    }

    public function debug(string $message, array $params = [])
    {
        $this->write->write(json_encode([
            "message" => $message,
            "params"  => $params
        ]), "debug");
    }
}