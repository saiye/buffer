<?php

namespace App\Http\Controller;

use App\Library\Application;
use App\Library\Contract\Request;

class BaseController
{
    protected $app;
    protected $request;

    public function __construct(Application $app, Request $request)
    {
        $this->app = $app;

        $this->request = $request;
    }
}
