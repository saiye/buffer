<?php

namespace Engine\Contracts\App;

interface App
{
    /**
     * Register a shared binding in the container.
     *
     * @param  string  $abstract
     * @param  \Closure|string|null  $concrete
     * @return void
     */
    public function singleton(string $abstract, $concrete = null);


    /**
     * Register an existing instance as shared in the container.
     *
     * @param  string  $abstract
     * @param  \Closure|string|null  $instance
     * @return mixed
     */
    public function instance(string $abstract, \stdClass $instance);


    /**
     * Resolve the given type from the container.
     *
     * @param  string  $abstract
     * @param  \Closure|string|null  $parameters
     * @return mixed
     *
     * @throws \Engine\Contracts\Container\BindingResolutionException
     */
    public function make($abstract, $parameters = null);

}