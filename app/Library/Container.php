<?php

namespace App\Library;

use Closure;
use Exception;
use ReflectionClass;
use ReflectionException;
use ReflectionParameter;

class Container
{
    protected $bindings = [];
    protected $instances = [];

    /**
     *  绑定类
     * @param  $abstract
     * @param null $concrete
     * @param bool $shared
     * @return void
     */
    public function bind($abstract, $concrete = null, bool $shared = false): void
    {
        if (is_null($concrete)) {
            $concrete = $abstract;
        }
        if (is_a($concrete,$abstract)) {
            $this->instances[$abstract] = $concrete;
            return;
        }
        if (!$concrete instanceof Closure) {
            $concrete = function ($container) use ($concrete) {
                /**
                 * @var $container  Container
                 */
                return $container->build($concrete);
            };
        }

        $this->bindings[$abstract] = compact('concrete', 'shared');
    }

    /**
     * 绑定单例类
     * @param  $abstract
     * @param null $concrete
     * @return void
     */
    public function singleton($abstract, $concrete = null): void
    {
        $this->bind($abstract, $concrete, true);
    }

    /**
     * @throws ReflectionException
     */
    public function make($abstract, $parameters = [])
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

        if (isset($this->bindings[$abstract]['shared'])&&$this->bindings[$abstract]['shared']) {
            $this->instances[$abstract] = $object;
        }

        return $object;
    }

    protected function isBuildable($concrete): bool
    {
        return $concrete instanceof Closure || is_string($concrete);
    }


    /**
     * 构建类实例
     * @param $concrete
     * @param array $parameters
     * @return mixed|object|null
     * @throws ReflectionException
     */
    private function build($concrete, array $parameters = [])
    {
        if ($concrete instanceof Closure) {
            return $concrete($this, ...$parameters);
        }

        $reflector = new ReflectionClass($concrete);

        if (!$reflector->isInstantiable()) throw new Exception("Class $concrete is not instantiable");

        //获取构造函数
        $constructor = $reflector->getConstructor();

        if (is_null($constructor)) {
            return new $concrete;
        }

        //获取构造函数的参数依赖
        $dependencies = $constructor->getParameters();
        //参数依赖匹配
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
     * 获取依赖
     * @throws Exception
     */
    protected function getDependencies(array $parameters, array $primitives = []): array
    {
        $dependencies = [];

        foreach ($parameters as $parameter) {
            $dependency = $parameter->getType();
            if (array_key_exists($parameter->name, $primitives)) {
                $dependencies[] = $primitives[$parameter->name];
            } else{
                if ($dependency->isBuiltin()){
                    $dependencies[] = $this->resolveNonClass($parameter);
                }else{
                    $dependencies[] = $this->make($dependency->getName());
                }
            }
        }

        return $dependencies;
    }

    /**
     * 获取默认参数值
     * @param ReflectionParameter $parameter
     * @throws Exception
     */
    protected function resolveNonClass(ReflectionParameter $parameter)
    {
        if ($parameter->isDefaultValueAvailable()) {
            return $parameter->getDefaultValue();
        }

        throw new Exception("Unresolvable dependency resolving [$parameter]");
    }


    /**
     * 参数格式化
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
