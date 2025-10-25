<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class ValidateRoute
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $route = $request->route();
        
        if ($route && $route->getName()) {
            // Route exists and has a name
            return $next($request);
        }

        // Log the request for debugging
        Log::info('Route validation', [
            'path' => $request->path(),
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'route_name' => $route?->getName(),
            'user_agent' => $request->userAgent(),
            'ip' => $request->ip()
        ]);

        return $next($request);
    }
}
