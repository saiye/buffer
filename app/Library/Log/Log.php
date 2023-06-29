<?php

namespace App\Library\Log;

use App\Library\Application;
use App\Library\Contract\Logger;

class Log implements Logger
{

    private $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function warning(string $data): void
    {
        $this->write('warning', $data);
    }

    public function error(string $data): void
    {
        $this->write('error', $data);
    }

    public function info(string $data): void
    {
        $this->write('info', $data);
    }

    /**
     * 写入内存，减少IO
     * @param string $type
     * @param string $data
     * @return void
     */
    public function write(string $type, string $data): bool
    {
        $today = date('Ymd');
        $date = date('Y-m-d H:i:s');
        file_put_contents($this->app->getPath('path.logs') . DIRECTORY_SEPARATOR . $today . '.log', '[' . $date . '] ' . $type . PHP_EOL . $data . PHP_EOL, FILE_APPEND);
        return true;
    }
}
