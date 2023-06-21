<?php

namespace App\Http\Middleware;

use App\Library\Contract\Middleware;
use App\Library\Contract\Request;
use Closure;

class AuthMiddleware implements Middleware
{
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $header = $request->getHeaders();
        $token = $header['token'] ?? '';
        if ($this->checkToken($token)) {
            return $next($request);
        }
        throw new \Exception("not login");
    }

    public function checkToken(string $token): bool
    {
        //todo

        return $token == 'buffer';
    }

}
