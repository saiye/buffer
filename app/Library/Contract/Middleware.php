<?php

namespace App\Library\Contract;

use Closure;

interface  Middleware
{
    public function handle(Request $request, Closure $next, ...$guards);
}
