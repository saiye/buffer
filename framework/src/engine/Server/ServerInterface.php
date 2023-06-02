<?php

declare(strict_types=1);

namespace Engine\Server;
interface ServerInterface
{

    /**
     * 构造函数
     * @param  string  $processName
     * @param  int  $workerNum
     */
    public function __construct(string $processName, int $workerNum = 40);

    public function start();
    public function close();
    public function connect();
    public function receive();

}