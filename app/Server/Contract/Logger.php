<?php

namespace App\Server\Contract;

interface Logger
{
    public function warning(string $data): void;

    public function error(string $data): void;
    public function info(string $data): void;
}
