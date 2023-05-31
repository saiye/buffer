<?php

namespace App\Server;

use Closure;
use Exception;
use ReflectionClass;
use ReflectionException;
use ReflectionParameter;

class AppContainer
{
    protected array $bindings = [];
    protected array $instances = [];

    /**
     * @param mixed $abstract
     * @param mixed|null $concrete
     * @param bool $shared
     * @return void
     */
    public function bind(mixed $abstract, mixed $concrete = null, bool $shared = false): void
    {
        if (is_null($concrete)) {
            $concrete = $abstract;
        }

        if (!$concrete instanceof Closure) {
            $concrete = function ($container) use ($concrete) {
                /**
                 * @var $container  AppContainer
                 */
                return $container->build($concrete);
            };
        }

        $this->bindings[$abstract] = compact('concrete', 'shared');
    }

    /**
     * @param mixed $abstract
     * @param mixed|null $concrete
     * @return void
     */
    public function singleton(mixed $abstract, mixed $concrete = null): void
    {
        $this->bind($abstract, $concrete, true);
    }

    /**
     * @throws ReflectionException
     */
    public function make($abstract, $parameters = []): mixed
    {
        if (isset($this->instances[$abstract])) {
            return $this->instances[$abstract];
        }

        if (isset($this->bindings[$abstract])) {
            $concrete = $this->bindings[$abstract]['concrete'];
        } else {
            $concrete = $abstract;
        }

        if ($this->isBuildable($concrete)) {
            $object = $this->build($concrete, $parameters);
        } else {
            $object = $this->make($concrete, $parameters);
        }

        if ($this->bindings[$abstract]['shared']) {
            $this->instances[$abstract] = $object;
        }

        return $object;
    }

    protected function isBuildable($concrete): bool
    {
        return $concrete instanceof Closure || is_string($concrete);
    }


    /**
     * @param mixed $concrete
     * @param array $parameters
     * @return mixed
     * @throws ReflectionException
     * @throws Exception
     */
    public function build(mixed $concrete, array $parameters = []): mixed
    {
        if ($concrete instanceof Closure) {
            return $concrete($this, ...$parameters);
        }

        $reflector = new ReflectionClass($concrete);

        if (!$reflector->isInstantiable()) throw new Exception("Class $concrete is not instantiable");

        $constructor = $reflector->getConstructor();

        if (is_null($constructor)) {
            return new $concrete;
        }

        $dependencies = $constructor->getParameters();
        $parameters = $this->keyParametersByArgument(
            $dependencies,
            $parameters
        );

        $instances = $this->getDependencies(
            $dependencies,
            $parameters
        );

        return $reflector->newInstanceArgs($instances);
    }

    /**
     * @throws Exception
     */
    protected function getDependencies(array $parameters, array $primitives = []): array
    {
        $dependencies = [];

        foreach ($parameters as $parameter) {
            $dependency = $parameter->getClass();

            if (array_key_exists($parameter->name, $primitives)) {
                $dependencies[] = $primitives[$parameter->name];
            } elseif (!is_null($dependency)) {
                $dependencies[] = $this->make($dependency->name);
            } else {
                $dependencies[] = $this->resolveNonClass($parameter);
            }
        }

        return $dependencies;
    }

    /**
     * @param ReflectionParameter $parameter
     * @return mixed
     * @throws Exception
     */
    protected function resolveNonClass(ReflectionParameter $parameter): mixed
    {
        if ($parameter->isDefaultValueAvailable()) {
            return $parameter->getDefaultValue();
        }

        throw new Exception("Unresolvable dependency resolving [$parameter]");
    }


    /**
     * @param array $dependencies
     * @param array $parameters
     * @return array
     */
    protected function keyParametersByArgument(array $dependencies, array $parameters): array
    {
        foreach ($parameters as $key => $value) {
            if (is_numeric($key)) {
                unset($parameters[$key]);

                $parameters[$dependencies[$key]->name] = $value;
            }
        }

        return $parameters;
    }

}
