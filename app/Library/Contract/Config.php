<?php

namespace App\Library\Contract;

interface Config
{
    public function get(string $command,$default=null);
}
