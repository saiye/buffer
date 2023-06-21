<?php

namespace App\Library;

use App\Library\Config\Config;
use App\Library\Provider\HttpServerProvider;
use App\Library\Provider\InitServerProvider;

class Application extends Container
{
    const VERSION = '1.0.0';
    protected $path = [];

    private static $app;

    private function __construct()
    {
        //基础服务提供者注册
        (new InitServerProvider($this))->register();

        (new HttpServerProvider($this))->register();

        //应用提供者注册
        $config = $this->make(Config::class);

        $providers = $config->get('app.providers');
        if (is_array($providers)){
            foreach ($providers as $provider) {
                (new $provider($this))->register();
            }
        }
    }

    public static function getApplication(): Application
    {
        if (self::$app == null) {
            self::$app = new self();
        }
        return self::$app;
    }

    public function getPath(string $abstract): string
    {
        return $this->path[$abstract] ?? '';
    }

    public function setPath(string $abstract, string $path): bool
    {
        if (is_file($path) || is_dir($path)) {
            $this->path[$abstract] = $path;
            return true;
        }
        return false;
    }

    public function version()
    {
        return static::VERSION;
    }
}
