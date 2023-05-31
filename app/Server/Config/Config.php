<?php

namespace App\Server\Config;

use App\Server\Contract\Config as ConfigBase;

class Config implements ConfigBase
{
    private $data = [];

    public function get(string $command): mixed
    {
        $pot = strpos($command, '.');
        if ($pot === false) {
            return $this->getData($command, '');
        } else {
            return $this->getData(substr($command, 0, $pot), substr($command, $pot));
        }
    }

    private function getData(string $fileName, string $keys): mixed
    {
        $path='';
    }
}
