<?php

namespace App\Library\Request;

use App\Library\Contract\Request as base;
use Swoole\Http\Request;

class SwooleRequest implements base
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function getMethod(): string
    {
        return $this->request->server['request_method'];
    }

    public function getUri(): string
    {
        return $this->request->server['request_uri'];
    }

    public function getHeaders(): array
    {
        return $this->request->header;
    }

    public function getBody(): string
    {
        return $this->request->rawContent();
    }

    public function __get($name)
    {
        return $this->request->$name;
    }

    public function __call($name, $arguments)
    {
        return $this->request->$name(...$arguments);
    }

    public function isWebSocketUpgrade(): bool
    {
        return false;
    }

}
