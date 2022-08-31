<?php

namespace Engine\Contracts\Write;

interface Write
{
    public function write(string $message, int $type);

    public function getMessage(): string;
}