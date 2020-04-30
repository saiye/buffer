<?php
/**
 * Created by 2020/3/20 0020 20:50
 * User: yuansai chen
 */

namespace App\Server;
use App\Base;


class Http extends  Base{

    function run()
    {
        $http = new \Swoole\Http\Server("0.0.0.0", 9501);

        $http->set([
            'document_root' => '/mnt/web/test', // v4.4.0以下版本, 此处必须为绝对路径
            'enable_static_handler' => true,
            'task_enable_coroutine' => true,
            'task_worker_num' => 8,
        ]);

        $http->on('request', function ($request, $response) use ($http) {
            var_dump($request->get, $request->post);
            $task_id = $http->task('fuck task');
            $response->header("Content-Type", "text/html; charset=utf-8");
            $response->end("<h1>Hello Swoole. #".rand(1000, 9999)."----".$task_id."</h1>");
        });



        $http->on('Task', function (\Swoole\Server $server, \Swoole\Server\Task $task) {
            echo "Tasker进程接收到数据".$task->data;
           // $server->finish('run task');
            return "task";
        });

        $http->on('Finish', function (\Swoole\Server $server, int $task_id, string $data) {
            echo "Tasker进程触发finsh事件：".$data;
            return 'ok';
        });



  /*      $http->on('WorkerStart', function(\Swoole\Server $server, int $workerId) {
           // var_dump(get_included_files()); //此数组中的文件表示进程启动前就加载了，所以无法reload
            $server->tick(1000, function ($id) {
                var_dump($id);
            });
        });*/

        $http->start();
    }


}