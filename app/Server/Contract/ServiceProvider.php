<?php

namespace App\Server\Contract;

use App\Server\AppContainer;

abstract class  ServiceProvider
{
    /**
     * @var AppContainer
     */
    protected $app;
    public function __construct($app)
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
