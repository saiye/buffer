<?php

namespace App\Library\Request;

use App\Library\Contract\Request;

class StreamRequest implements Request
{
    private $params = [];

    public function __construct(array $params)
    {
        $this->params = $params;
    }

    public function getMethod(): string
    {
        return $this->params['method'] ?? 'get';
    }

    public function getUri(): string
    {
        return $this->params['uri'] ?? '/';
    }

    public function getHeaders(): array
    {
        return $this->params['headers'] ?? [];
    }

    public function getBody(): string
    {
        return $this->params['body'] ?? '';
    }

    public function isWebSocketUpgrade(): bool
    {
        return false;
    }

}
