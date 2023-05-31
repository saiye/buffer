<?php
use App\Server\HttpSwooleServer;

define('APP_START', microtime(true));

require __DIR__ . '/../vendor/autoload.php';


require_once __DIR__ . '/../app/app.php';


$host = '0.0.0.0';
$port = 9505;
$mode = SWOOLE_PROCESS;
$sockType = SWOOLE_SOCK_TCP;
$options = [
    'log_file' => "swoole.log",
];
$server = new HttpSwooleServer($host, $port, $mode, $sockType, $options);

$server->start();

