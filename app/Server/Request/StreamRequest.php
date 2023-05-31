<?php

namespace App\Server\Request;

use App\Server\Contract\Request;

class StreamRequest implements Request
{
    private $params =[];

    public function __construct(array $params)
    {
        $this->params = $params;
    }

    public function getMethod(): string
    {
        // TODO: Implement getMethod() method.
    }

    public function getUri(): string
    {
        // TODO: Implement getUri() method.
    }

    public function getHeaders(): array
    {
        // TODO: Implement getHeaders() method.
    }

    public function getBody(): string
    {
        // TODO: Implement getBody() method.
    }

}
