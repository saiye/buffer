<?php
/**
 * Created by 2020/3/20 0020 20:50
 * User: yuansai chen
 */

namespace App\Process;
use App\Base;
class Parents extends  Base{

    function run()
    {
        //实现了一个简单的父子进程通讯
        $proc1 = new \Swoole\Process(function (\swoole_process $proc) {
            $socket = $proc->exportSocket();
            echo $socket->recv();
            $socket->send("hello master\n");
            echo "proc1 stop\n";
        }, false, 1, true);

        $proc1->start();

         //父进程创建一个协程容器
        \Co\run(function() use ($proc1) {
            $socket = $proc1->exportSocket();
            $socket->send("hello pro1\n");
            var_dump($socket->recv());
        });
        \Swoole\Process::wait(true);
    }
}