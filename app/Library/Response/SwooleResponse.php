<?php

namespace App\Library\Response;

use App\Library\Contract\Response as BaseResponse;

class SwooleResponse implements BaseResponse
{
    use ResponseTrait;

    public function end(): void
    {
        foreach ($this->header as $k => $v) {
            $this->socket->header($k, $v);
        }
        $this->socket->status($this->statusCode);
        $this->socket->write($this->content);
        $this->socket->end();
    }

    public function __get($name)
    {
        return $this->socket->$name;
    }

    public function __call($name, $arguments)
    {
        return $this->socket->$name(...$arguments);
    }
}
