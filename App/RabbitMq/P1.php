<?php
/**
 * Created by 2020/3/20 0020 20:50
 * User: yuansai chen
 */

namespace App\RabbitMq;
use App\Base;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class P1 extends  Base{

    function run()
    {
        //远程访问必须设置用户权限,本地可以用guest用户访问

        $host='192.168.3.2';
       // $host='localhost';
//建立一个连接通道，声明一个可以发送消息的队列hello
       // $connection = new AMQPStreamConnection($host, 5672, 'guest', 'guest');
        $connection = new AMQPStreamConnection($host, 5672, 'yuansai', '123456');
        $channel = $connection->channel();
        $channel->queue_declare('hello', false, false, false, false);

//定义一个消息，消息内容为Hello World!
        $msg = new AMQPMessage('Hello World!');
        $channel->basic_publish($msg, '', 'hello');

//发送完成后打印消息告诉发布消息的人：发送成功
        echo " [x] Sent 'Hello World!'\n";
//关闭连接
        $channel->close();
        $connection->close();

    }
}