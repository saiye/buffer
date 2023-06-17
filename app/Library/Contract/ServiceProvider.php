<?php

namespace App\Library\Contract;

use App\Library\Application;
use App\Library\Container;

abstract class  ServiceProvider
{
    /**
     * @var Container
     */
    protected $app;
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    abstract public function register();

}
