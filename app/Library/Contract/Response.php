<?php

namespace App\Library\Contract;

interface Response
{
    public function setHeader(string $name, string $value): void;

    public function setStatusCode(int $statusCode): void;

    public function write(string $content): void;

    public function end(): void;

    public  function  getSocket();
}
