<?php

namespace App\Http\Middleware;

use Services\UserService;
use Illuminate\Http\Request;
use Closure;

class ScopeAmbassadorMiddleware
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
        $userService = (new UserService())->getRequest('get', 'scope/ambassador');

        if (!$userService->ok()) {
            abort(401, 'unauthorized');
        }

        return $next($request);
    }
}
