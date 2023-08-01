<?php

namespace App\Library\Contract;

interface HttpServerContract
{
    public function onRequest(Request $request, Response $response): void;

    public function onHandShake(Request $request, Response $response): bool;

    public function start(): void;
}

