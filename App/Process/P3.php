<?php
/**
 * Created by 2020/3/20 0020 20:50
 * User: yuansai chen
 */

namespace App\Process;
use App\Base;

class P3 extends  Base{

    function run()
    {
        $num=swoole_cpu_num();
        dd('核数:'.$num);
    }
}