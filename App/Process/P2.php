<?php
/**
 * Created by 2020/3/20 0020 20:50
 * User: yuansai chen
 */

namespace App\Process;
use App\Base;
use Swoole\Process;
use Swoole\Timer;

class P2 extends  Base{

    function run()
    {

        $process = new Process(function ($proc) {
            \Swoole\Timer::tick(1000, function () use ($proc) {
                $socket = $proc->exportSocket();
                $socket->send("hello master\n");
                echo "child timer\n";
            });
        }, false, 1, true);

        $process->start();

        \Co\run(function() use ($process) {
            Process::signal(SIGCHLD, static function ($sig) {
                while ($ret = Swoole\Process::wait(false)) {
                    /* clean up then event loop will exit */
                    Process::signal(SIGCHLD, null);
                    Timer::clearAll();
                }
            });
            /* your can run your other async or coroutine code here */
            Timer::tick(500, function () {
                echo "parent timer\n";
            });

            $socket = $process->exportSocket();
            while (1) {
                var_dump($socket->recv());
            }
        });
    }
}