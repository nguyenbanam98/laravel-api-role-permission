<?php

namespace WeSimplyCode\ApiRolePermission\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class HasAllPermissions
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $permission)
    {
        if (Auth::guard('api')->check()) {
            $user = Auth::guard('api')->user();

            $permissions = is_array($permission)
                ? $permission
                : explode('|', $permission);


            if (!$user->hasAllPermissions($permissions)) {
                return response(['message' => 'Unauthorized!'], 401);
            } else {
                Auth::login($user);
                return $next($request);
            }
        } else {
            return response(['message' => 'Unauthorized!'], 401);
        }
    }
}
