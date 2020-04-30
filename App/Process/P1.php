<?php
/**
 * Created by 2020/3/20 0020 20:50
 * User: yuansai chen
 */

namespace App\Process;
use App\Base;
use Swoole\Process;
use Swoole\Timer;

class P1 extends  Base{

    function run()
    {
        //pcntl_forck 传统进程
        $arr=xrange(1,10);
        foreach ($arr as $i){
           $pid= pcntl_fork();
            if ($pid == -1) {
                //错误处理：创建子进程失败时返回-1.
                die('could not fork');
            } else if ($pid) {
                //父进程会得到子进程号，所以这里是父进程执行的逻辑
                pcntl_wait($status); //等待子进程中断，防止子进程成为僵尸进程。
                //dd('master---pid:'.$pid);
            } else if ($pid==0) {
                //子进程得到的$pid为0, 所以这里是子进程执行的逻辑。
                dd("第{$i}次，执行---pid:".$pid);
                $child_pid = getmypid ();
                posix_kill ( $child_pid, SIGCHLD );
            }
        }


    }
}