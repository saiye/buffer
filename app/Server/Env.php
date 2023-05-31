<?php

namespace App\Server;
use App\Server\Contract\EnvContract;

class Env implements  EnvContract
{

    private array $data;

    public function __construct(string $path)
    {
        $this->data = $this->loadEnvData($path);
    }

    public function env(string $key, string $default = ''): mixed
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
            $str = file_get_contents($path);
            foreach (explode('\n', $str) as $lin) {
                $res = explode('=', $lin);
                if (count($res) == 2) {
                    $data[trim($res[0])] = trim($res[1]);
                }
            }
        }
        return $data;
    }
}
