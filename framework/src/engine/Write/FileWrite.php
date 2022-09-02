<?php

declare(strict_types=1);

namespace Engine\Write;

use Engine\Contracts\Write\Write as ContractsWrite;

class FileWrite implements ContractsWrite
{
    /**
     * 消息
     * @var string
     */
    private string $message;
    /**
     * 基础路径
     * @var string
     */
    private string $baseDir;


    /**
     * @param  string  $baseDir
     * @throws \Exception
     */
    public function __construct(string $baseDir = "")
    {
        if (empty($baseDir)) {
            throw  new \Exception("cant find log path");
        }
        $this->baseDir = $baseDir;
    }

    /**
     * 获取文件名称
     * @return string
     */
    public function getFileName(): string
    {
        return $this->baseDir.DIRECTORY_SEPARATOR.date("Y-m-d").".log";
    }

    /**
     * 写入消息
     * @param  string  $message
     * @param  int  $type
     * @return void
     */
    public function write(string $message, int $type)
    {
        $this->message = $message;
        //每天一个log
        $file = $this->getFileName();
        //追加消息
        file_put_contents($file, $message.PHP_EOL, FILE_APPEND);

    }

    /**
     * 获取消息
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }
}