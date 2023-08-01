<?php

namespace App\Library\Contract;

interface SocketEvent
{
    public function data():array;
    public function type():int;
}
