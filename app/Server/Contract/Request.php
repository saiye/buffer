<?php

namespace App\Server\Contract;

interface Request
{

    public function getMethod(): string;
    public function getUri(): string;
    public function getHeaders(): array;
    public function getBody(): string;
}
