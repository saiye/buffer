<?php

namespace Engine\Server;

interface WebSocketInterface extends HttpServerInterface
{
    public function  message();
    public function  open();
}