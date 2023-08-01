<?php

namespace App\Socket;

use App\Library\Contract\SocketEvent;

class EventBase implements SocketEvent
{
    protected $data = [];
    protected $type = 0;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function data(): array
    {
        return $this->data;
    }

    public function type(): int
    {
        return $this->type;
    }
}
