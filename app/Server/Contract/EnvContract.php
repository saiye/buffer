<?php

namespace App\Server\Contract;

interface EnvContract
{
    public function env(string $key, string $default = ''): mixed;
}
