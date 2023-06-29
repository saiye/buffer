<?php

namespace App\Library\Response;

use App\Library\Contract\Response as BaseResponse;

/**
 * versatility code
 */
trait ResponseTrait
{

    private $socket;

    private $header = [];
    private $statusCode = 200;
    private $content = '';

    public function setHeader(string $name, string $value): BaseResponse
    {
        $this->header[$name] = $value;
    }

    public function getHeader(): array
    {
        return $this->header;
    }

    public function fullHeaders(array $header): BaseResponse
    {
        $this->header = $header;

        return $this;
    }

    public function setStatusCode(int $statusCode): BaseResponse
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function setContent(string $content): BaseResponse
    {
        $this->content = $content;
        return $this;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setSocket($socket): BaseResponse
    {
        $this->socket = $socket;
        return $this;
    }

    public function getSocket()
    {
        return $this->socket;
    }

}
