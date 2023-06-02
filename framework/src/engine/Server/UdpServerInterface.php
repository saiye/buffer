<?php

namespace Engine\Server;

interface UdpServerInterface extends ServerInterface
{
    public function  packet();
}