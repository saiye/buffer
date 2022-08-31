<?php

declare(strict_types=1);

namespace Engine;

use Engine\App\Application;
use Engine\Write\FileWrite;

class App
{
    /**
     * @var \Engine\App\Application
     */
    public static $app;

    /**
     * @return \Engine\App\Application
     */
    public static function getApp(): Application
    {
        if (!self::$app) {
            self::$app = new Application();
        }
        return self::$app;
    }

    public function register()
    {
        $bindRes = [
            "Log" => FileWrite::class,
        ];
        foreach ($bindRes as $abstract => $instance) {
            self::getApp()->instance($abstract, $instance);
        }
    }

    public function run()
    {
        $this->register();
    }
}