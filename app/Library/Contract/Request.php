<?php

namespace App\Library\Contract;

interface Request
{

    public function getMethod(): string;
    public function getUri(): string;
    public function getHeaders(): array;
    public function getBody(): string;
    public function input(string $key='',$default=null);
    public function cookie(string $key='',$default=null);
    public function files():array;
    public function ip():string;
    public function uri():string;

    public function isWebSocketUpgrade():bool;
    public function getSocket();
}
