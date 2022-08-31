<?php

declare(strict_types=1);

namespace Engine\Write;

use Engine\Contracts\Write\Write as ContractsWrite;

class ArrayWrite implements ContractsWrite
{
    /**
     * 消息
     * @var string
     */
    private string $message;

    /**
     * 写入消息
     * @param  string  $message
     * @param  int  $type
     * @return void
     */
    public function write(string $message, int $type)
    {
        $this->message = $message;
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