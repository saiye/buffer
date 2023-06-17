<?php

namespace App\Library;

use App\Library\Contract\EnvContract;

class Env implements EnvContract
{
    private $data;

    public function __construct($path)
    {
        $this->data = $this->loadEnvData($path);
    }

    public function env(string $key, $default = '')
    {
        $keyRes = explode('.', $key);
        $currData = $default;
        foreach ($keyRes as $v) {
            $currData = $this->data[$v] ?? $default;
            if (!is_array($currData)) {
                break;
            }
        }
        return $currData;
    }

    public function loadEnvData(string $path): array
    {
        $data = [];
        if (is_file($path)) {
            $data = parse_ini_file($path);
        }
        return $data;
    }
}
