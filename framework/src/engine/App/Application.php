<?php

declare(strict_types=1);


namespace Engine\App;

use Engine\Contracts\App\App;
use Engine\Contracts\Container\RegObjException;

class Application implements App
{

    public static $map = [];
    public $instance = [];
    public static $singleton = [];
    //单例
    const Type_Single = 1;
    //实例
    const Type_Instance = 2;

    public function __construct()
    {
    }

    /**
     * Register a shared binding in the container.
     * @param  string  $abstract
     * @param  \Closure|string|null  $concrete
     * @return void
     * @throws \Engine\Contracts\Container\RegObjException
     */
    public function singleton(string $abstract, $concrete = null): void
    {
        if (!isset(self::$map[$abstract])) {
            self::$map[$abstract] = [
                "type"     => self::Type_Single,
                "concrete" => $concrete
            ];
            if (is_object($concrete)){
                self::$singleton[$abstract]=$concrete;
            }
        } else {
            throw  new RegObjException("重复注册类:".$abstract);
        }
    }


    /**
     * Register an existing instance as shared in the container.
     * @param  string  $abstract
     * @param  \Closure|string|null  $instance
     * @return void
     * @throws \Engine\Contracts\Container\RegObjException
     */
    public function instance(string $abstract, $instance): void
    {
        if (!isset(self::$map[$abstract])) {
            self::$map[$abstract] = [
                "type"     => self::Type_Instance,
                "concrete" => $instance
            ];
        } else {
            throw  new RegObjException("重复注册类:".$abstract);
        }
    }

    /**
     * 容器解析对象
     * @param $abstract
     * @param $parameters
     * @return mixed|null
     * @throws \Engine\Contracts\Container\RegObjException
     */
    public function make($abstract, $parameters = null)
    {
        $obj = self::$singleton[$abstract]??$this->instance[$abstract] ?? null;
        if ($obj) {
            return $obj;
        }
        $config = self::$map[$abstract] ?? null;
        if (!$config) {
            throw  new RegObjException("未注册".$abstract);
        }
        return $this->resolve($config, $abstract, $parameters);
    }

    public function resolve($config, $abstract, $parameters)
    {
        if ($config["concrete"] instanceof \Closure) {
            $obj = call_user_func($config["concrete"], $parameters);
            if ($config['type'] == self::Type_Single) {
                self::$singleton[$abstract] = $obj;
            } else {
                $this->instance[$abstract] = $obj;
            }
            return $obj;
        }
        return $this->instance[$abstract];
    }

}