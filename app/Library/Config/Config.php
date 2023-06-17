<?php

namespace App\Library\Config;

use App\Library\Container;
use App\Library\Contract\Config as ConfigBase;

class Config implements ConfigBase
{
    private $app;

    private static $data = [];

    public function __construct(Container $app)
    {
        $this->app = $app;
    }

    public function get(string $command, $default = null)
    {
        $pot = strpos($command, '.');
        if ($pot === false) {
            return $this->getData($command, '', $default);
        } else {
            return $this->getData(substr($command, 0, $pot), substr($command, $pot + 1), $default);
        }
    }

    public function config(string $command, $default)
    {
        return $this->get($command, $default);
    }

    private function getData(string $fileName, string $command, $default = null)
    {
        if (isset(self::$data[$fileName])) {
            return $this->env($fileName, $command, $default);
        }
        $file = $this->app->getPath('path.config.optimize');
        if (is_file($file)) {
            self::$data = require_once $file;
        } else {
            $file = $this->app->getPath('path.config') . DIRECTORY_SEPARATOR . $fileName . '.php';
            if (is_file($file)) {
                self::$data[$fileName] = require_once $file;
            } else {
                throw  new \Exception($file . " not find");
            }
        }
        return $this->env($fileName, $command, $default);
    }

    private function env(string $fileName, string $key, $default = '')
    {
        $keyRes = explode('.', $key);
        $currData = $default;
        foreach ($keyRes as $v) {
            $currData = self::$data[$fileName][$v] ?? null;
            if (!is_array($currData)) {
                break;
            }
        }
        return $currData;
    }
}
