<?php

namespace App\Http\Middleware;

use Services\UserService;
use Illuminate\Http\Request;
use Closure;

class ScopeAdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $userService = (new UserService())->getRequest('get', 'scope/admin');

        if (!$userService->ok()) {
            abort(401, 'unauthorized');
        }

        return $next($request);
    }
}
