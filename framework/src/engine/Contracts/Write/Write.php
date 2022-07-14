<?php

namespace Engine\Contracts\Write;

interface Write
{
    public function write(string $message, string $type);

    public function getMessage(): string;
}