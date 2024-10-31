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
     * @param  string  $role
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Check if the user is authenticated
        if (!$request->user()) {
            return response()->redirectToRoute('login'); // Redirect to login if not authenticated
        }

        // Check if the user has the specified role
        if (!$request->user()->hasRole($role)) {
            return response()->json(['error' => 'Unauthorized'], 403); // Return a 403 response if unauthorized
        }

        return $next($request); // Proceed with the request if the user has the role
    }
}