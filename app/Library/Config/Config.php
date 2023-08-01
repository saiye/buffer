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

    public function config(string $command, $default=null)
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
                self::$data[$fileName] = require $file;
            } else {
                throw  new \Exception($file . " not find");
            }
        }
        return $this->env($fileName, $command, $default);
    }

    private function env(string $fileName, string $key, $default = '')
    {
        $keyRes = explode('.', $key);
        $count = count($keyRes);
        switch ($count) {
            case 0:
                $currData = self::$data[$fileName] ?? $default;
                break;
            case 1:
                $currData = self::$data[$fileName][$keyRes[0]] ?? $default;
                break;
            case 2:
                $currData = self::$data[$fileName][$keyRes[0]][$keyRes[1]] ?? $default;
                break;
            case 3:
                $currData = self::$data[$fileName][$keyRes[0]][$keyRes[1]][$keyRes[2]] ?? $default;
                break;
            case 4:
                $currData = self::$data[$fileName][$keyRes[0]][$keyRes[1]][$keyRes[2]][$keyRes[3]] ?? $default;
                break;
            default;
                $currData = $default;
        }
        return $currData;
    }
}
