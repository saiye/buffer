<?php

namespace App\Server\Config;

use App\Server\AppContainer;
use App\Server\Contract\Config as ConfigBase;

class Config implements ConfigBase
{
    private $app;

    private $data = [];

    public function __construct(AppContainer $app)
    {
        $this->app = $app;
    }

    public function get(string $command, $default = null)
    {
        $pot = strpos($command, '.');
        if ($pot === false) {
            return $this->getData($command, '', $default);
        } else {
            return $this->getData(substr($command, 0, $pot), substr($command, $pot), $default);
        }
    }

    public function config(string $command, $default)
    {
        return $this->get($command, $default);
    }

    private function getData(string $fileName, string $command, $default = null)
    {
        if (isset($this->data[$fileName])) {
            return $this->env($fileName, $command, $default);
        }
        $file = $this->app->getPath('config.optimize.file');
        if (is_file($file)) {
            $this->data = require_once $file;
        } else {
            $file = $this->app->getPath('config') . DIRECTORY_SEPARATOR . $fileName . '.php';
            if (is_file($file)) {
                $this->data[$fileName] = require_once $file;
            }
        }
        return $this->env($fileName, $command, $default);
    }

    private function env(string $fileName, string $key, $default = '')
    {
        $keyRes = explode('.', $key);
        $currData = $default;
        foreach ($keyRes as $v) {
            $currData = $this->data[$fileName][$v] ?? null;
            if (!is_array($currData)) {
                break;
            }
        }
        return $currData;
    }
}
