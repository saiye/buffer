<?php

namespace App\Server;

use App\Server\Contract\EnvContract;

class Env implements EnvContract
{

    private array $data;

    public function __construct(string $path)
    {
        $this->data = $this->loadEnvData($path);
    }

    public function env(string $key, string $default = '')
    {
        $keyRes = explode('.', $key);
        $currData = $default;
        foreach ($keyRes as $v) {
            $currData = $this->data[$v] ?? null;
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
