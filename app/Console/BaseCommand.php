<?php

namespace App\Console;

use App\Library\Application;
use App\Library\Contract\Command;

abstract class BaseCommand implements Command
{
    protected $app;
    protected $option;

    public function __construct(Application $app, array $option)
    {
        $this->app = $app;
        $this->option = $option;
    }

    public function option(string $name = '')
    {
        if ($name == '') {
            return $this->option;
        }
        return $this->option[$name] ?? null;
    }
}
