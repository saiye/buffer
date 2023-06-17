<?php

namespace App\Library\Contract;

interface HttpServerContract
{
    public function onRequest(Request $request, Response $response): void;

    public function onHandShake(Request $request, Response $response): void;

    public function start(): void;
}

