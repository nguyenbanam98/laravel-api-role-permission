<?php

namespace WeSimplyCode\ApiRolePermission\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class HasAllRoles
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {
        if (Auth::guard('api')->check()) {
            $user = Auth::guard('api')->user();

            $roles = is_array($role)
                ? $role
                : explode('|', $role);


            if (!$user->hasAllRoles($roles)) {
                return response(['message' => 'Unauthorized!'], 401);
            } else {
                return $next($request);
            }
        } else {
            return response(['message' => 'Unauthorized!'], 401);
        }
    }
}
