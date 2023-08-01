<?php

namespace App\Console\Commands;

use App\Console\BaseCommand;

class TestCommand extends BaseCommand
{
    public function description(): string
    {
        return 'TestCommand';
    }

    public function run(): void
    {
        echo 'run ' . $this->name() . __FILE__;
    }
}
