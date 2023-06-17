<?php

namespace App\Library\Response;


use App\Library\Contract\Response;

class StreamResponse implements Response
{
    private $client;

    public function __construct($client)
    {
        $this->client = $client;
    }

    public function setHeader(string $name, string $value): void
    {
        fwrite($this->client, "$name: $value\r\n");
    }

    public function setStatusCode(int $statusCode): void
    {
        fwrite($this->client, "HTTP/1.1 $statusCode\r\n");
    }

    public function write(string $content): void
    {
        fwrite($this->client, $content);
    }

    public function end(): void
    {
        fwrite($this->client,   "\r\n");
    }

}
