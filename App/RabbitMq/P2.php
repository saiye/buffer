<?php
/**
 * Created by 2020/3/20 0020 20:50
 * User: yuansai chen
 */

namespace App\RabbitMq;
use App\Base;
use PhpAmqpLib\Connection\AMQPStreamConnection;
class P2 extends  Base{

    function run()
    {
        $host='192.168.3.2';
       // $host='localhost';

       // $connection = new AMQPStreamConnection($host, 5672, 'guest', 'guest');
        $connection = new AMQPStreamConnection($host, 5672, 'yuansai', '123456');
        $channel = $connection->channel();

        $channel->queue_declare('hello', false, false, false, false);

        echo ' [*] Waiting for messages. To exit press CTRL+C', "\n";
        $callback = function($msg) {
            echo " [x] Received ", $msg->body, "\n";
        };

        //在接收消息的时候调用$callback函数
        $channel->basic_consume('hello', '', false, true, false, false, $callback);

        while(count($channel->callbacks)) {
            $channel->wait();
        }
        //https://www.cnblogs.com/gongshun/p/10694659.html
        //rabbitmqctl.bat set_user_tags yuansai administrator
        //rabbitmqctl.bat set_permissions -p "/" yuansai ".*" ".*" ".*"
        //rabbitmqctl.bat list_permissions -p /

    }
}