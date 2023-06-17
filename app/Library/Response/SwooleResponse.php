<?php

namespace App\Library\Response;

use App\Library\Contract\Response as Base;
use Swoole\Http\Response;

class SwooleResponse implements Base
{
    private $response;

    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    public function setHeader(string $name, string $value): void
    {
        $this->response->header($name, $value);
    }

    public function setStatusCode(int $statusCode): void
    {
        $this->response->status($statusCode);
    }

    public function write(string $content): void
    {
        $this->response->write($content);
    }

    public function end(): void
    {
        $this->response->end();
    }

    public function __get($name)
    {
        return $this->response->$name;
    }

    public function __call($name, $arguments)
    {
        return $this->response->$name(...$arguments);
    }
}
