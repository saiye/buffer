<?php

namespace App\Http\Controller;

use App\Service\Game\LoginService;

class IndexController extends BaseController
{
    public function test()
    {
        return 'test';
    }

    public function index(LoginService $service)
    {
        return $service->gameList();
    }
}
