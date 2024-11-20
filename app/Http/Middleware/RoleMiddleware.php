<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next, $role)
    {
        if (auth()->user()->role !== $role) {
            return response()->json([
                'success' => false,
                'message' => "Unauthorized, You don't have permission Take This Action"
            ], 403);       }
        return $next($request);
    }
}