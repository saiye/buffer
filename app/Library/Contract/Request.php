<?php

namespace App\Library\Contract;

interface Request
{

    public function getMethod(): string;
    public function getUri(): string;
    public function getHeaders(): array;
    public function getBody(): string;

    public function isWebSocketUpgrade():bool;
}
