<?php

declare(strict_types=1);


namespace Engine\App;

use Engine\Contracts\App\App;
use Engine\Contracts\Container\RegObjException;

class Application implements App
{
    public static $classBox = [];

    //单例
    const Type_Single = 1;
    //非单例
    const Type_Instance = 2;
    //闭包函数
    const Type_Call = 3;

    public function __construct()
    {
    }

    /**
     * 单例
     * @param $abstract
     * @param $concrete
     * @return void
     */
    public function singleton($abstract, $concrete = null)
    {
        if (!isset(self::$classBox[$abstract])) {
            self::$classBox[$abstract] = [
                "obj"  => $concrete,
                "type" => self::Type_Single
            ];
        } else {
            throw  new RegObjException("已经注册过的类");
        }
    }

    public function instance($abstract, $instance)
    {
    }

    public function make($abstract, array $parameters = [])
    {
        $map = self::$classBox[$abstract] ?? null;
        if (!empty($map)) {
            switch ($map['type']) {
                case self::Type_Single:
                    return $map['obj'];
                case self::Type_Instance:
                    break;
                case self::Type_Call:
                    return call_user_func($map['obj'], $parameters);
                default:
            }
        }
        return null;
    }

}