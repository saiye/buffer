<?php

namespace App\Library\Log;

use App\Library\Application;
use App\Library\Contract\Logger;

class Log implements Logger
{
    private $cache = [];

    private $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function warning(string $data): void
    {
        $this->cache('warning', $data);
    }

    public function error(string $data): void
    {
        $this->cache('error', $data);
    }

    public function info(string $data): void
    {
        $this->cache('info', $data);
    }

    /**
     * 写入内存，减少IO
     * @param string $type
     * @param string $data
     * @return void
     */
    private function cache(string $type, string $data)
    {
        $this->cache[$type][] = $data;
    }

    public function write(): bool
    {
        //统一写入
        if ($this->cache) {
            $cache = $this->cache;
            $this->cache = [];
            $info = '';
            $date = date('Ymd H:i:s');
            foreach ($cache as $type => $list) {
                foreach ($list as $log) {
                    $info .= $date . '[' . $type . ']' . $log . PHP_EOL;
                }
            }
            $today=date('Ymd');
            file_put_contents($this->app->getPath('path.logs') . DIRECTORY_SEPARATOR . $today.'.log', $info, FILE_APPEND);
            return true;
        }
        return false;
    }
}
