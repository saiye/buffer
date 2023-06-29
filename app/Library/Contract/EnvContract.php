<?php

namespace App\Library\Contract;

interface EnvContract
{
    public function env(string $key, $default = null);
}
