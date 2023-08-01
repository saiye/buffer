<?php

namespace App\Library\Contract;

interface Command
{
    public function run(): void;

    //描述
    public function description(): string;

    public function option(string $name);
}
