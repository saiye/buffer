<?php

namespace App\Server\Log;

use App\Server\Contract\Logger;

class Log implements Logger
{
    public function warning(string $data): void
    {
        // TODO: Implement warning() method.
    }

    public function error(string $data): void
    {
        // TODO: Implement error() method.
    }

    public function info(string $data): void
    {
        // TODO: Implement info() method.
    }

    private function write(string $type, string $data)
    {

    }
}
