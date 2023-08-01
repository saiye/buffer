<?php

namespace App\Library\Contract;

interface Response
{
    public function setHeader(string $name, string $value): Response;

    public function fullHeaders(array $header): Response;

    public function getHeader(): array;

    public function setStatusCode(int $statusCode): Response;

    public function getStatusCode(): int;

    public function setContent(string $content): Response;

    public function getContent(): string;

    public function end(): void;

    public function setSocket($socket): Response;

    public function getSocket();

}
