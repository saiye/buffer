<?php

namespace App\Server\Contract;

interface Config
{
    public function get(string $command):mixed;
}
