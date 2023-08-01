<?php

namespace App\Http\Middleware;

use App\Exception\AuthException;
use App\Library\Contract\Middleware;
use App\Library\Contract\Request;
use Closure;

class AdminAuthMiddleware implements Middleware
{
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $token = $request->input('token');
        if ($token && $this->checkToken($token)) {
            return $next($request);
        }
        throw new AuthException("admin not login");
    }

    public function checkToken(string $token): bool
    {
        //todo

        return $token == 'admin';
    }

}
