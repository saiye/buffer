<?php
/**
 * Created by 2020/3/20 0020 19:30
 * User: yuansai chen
 */
namespace App\Process;
use App\Base;
use Swoole\Process;

class Test extends  Base{

    public function run()
    {

      for ($n = 1; $n <= 10; $n++) {
            $process = new Process(function () use ($n) {
                echo 'Child #' . getmypid() . " start and sleep {$n}s" . PHP_EOL;
                sleep($n);
                echo 'Child #' . getmypid() . ' exit' . PHP_EOL;
            });
            $process->start();
        }
        dd('child id is -'.$process->pid);

        for ($n = 10; $n--;) {
            $status = Process::wait(true);
            echo "Recycled #{$status['pid']}, code={$status['code']}, signal={$status['signal']}" . PHP_EOL;
        }
        echo 'Parent #' . getmypid() . ' exit' . PHP_EOL;
    }
}