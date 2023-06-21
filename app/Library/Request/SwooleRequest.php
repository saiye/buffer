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

    public function ip(): string
    {
        return $this->request->server['remote_addr'] ?? '';
    }

    public function uri(): string
    {
        return $this->request->server['request_uri'] ?? '/';
    }

    public function getSocket()
    {
        return $this->request;
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

    public function input(string $key = '', $default = null)
    {
        $contentType = $this->request->header['content-type'] ?? null;
        if ($contentType == 'application/json') {
            $json = $this->getBody();
            $input = json_decode($json, true);
        } else {
            $method = $this->request->server['request_method'];
            switch ($method) {
                case 'GET':
                    $input = $this->request->get;
                    break;
                case 'POST':
                    $input = $this->request->post;
                    break;
                default:
                    $att = strtolower($method);
                    $input = $this->request->$att ?? [];
            }
        }
        if ($key !== '') {
            return $input[$key] ?? $default;
        }
        return $input;
    }

    public function cookie(string $key = '', $default = null)
    {
        if ($key !== '') {
            return $this->request->cookie[$key] ?? $default;
        }
        return $this->request->cookie;
    }

    public function files(): array
    {
        return $this->request->files;
    }

}
