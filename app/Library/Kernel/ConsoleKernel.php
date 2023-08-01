<?php

namespace App\Library\Kernel;

use App\Library\Application;
use App\Library\Contract\Kernel;

abstract class ConsoleKernel implements Kernel
{
    protected $app;
    protected $bootstrap = [];

    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->app->setRunConsole();

    }

    public function start()
    {
        global $argv;
        $count = count($argv);
        if ($count > 1 && $argv[0] == 'buffer') {
            $command = ucfirst($argv[1]);
            if ($command !== 'Swoole') {
                //SWOOLE should boot the program in the WORKER process
                $this->bootstrap();
            }
            $option = $this->formatOption($argv);
            $commandClass = $this->app->make('App\\Console\\Commands\\' . $command . 'Command', [
                'option' => $option,
            ]);
            $commandClass->run();
        }
    }

    /**
     * 获取完整的命令行参数
     * @param array $argv
     * @return array
     */
    private function formatOption(array $argv): array
    {
        $options = [];
        foreach ($argv as $i => $v) {
            $o = strpos($v, '-');
            if (is_int($o)) {
                $options[substr($v, $o + 1)] = $argv[$i + 1] ?? null;
            }
        }
        return $options;
    }

    public function bootstrap()
    {
        foreach ($this->bootstrap as $class) {
            (new $class())->bootstrap($this->app);
        }
    }
}
