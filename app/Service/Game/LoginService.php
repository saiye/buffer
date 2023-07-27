<?php

namespace App\Service\Game;

class LoginService
{
    private $pushService;

    public function __construct(PushService $service)
    {
        $this->pushService = $service;
    }

    public function gameList(): array
    {
        return $this->pushService->push();
    }
}
