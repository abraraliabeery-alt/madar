<?php

namespace App\Services;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class RouteFallbackService
{
    /**
     * Handle missing routes by checking all route files
     */
    public static function handleMissingRoute(Request $request, $routeName = null)
    {
        $path = $request->path();
        $method = $request->method();
        
        // Log the missing route for debugging
        Log::warning("Missing route accessed", [
            'route_name' => $routeName,
            'path' => $path,
            'method' => $method,
            'url' => $request->fullUrl(),
            'user_agent' => $request->userAgent(),
            'ip' => $request->ip()
        ]);

        // Check if this might be a route that should exist
        $suggestedRoute = self::suggestRoute($path, $method);
        
        if ($suggestedRoute) {
            return response()->view('errors.404', [
                'suggestedRoute' => $suggestedRoute,
                'originalPath' => $path
            ], 404);
        }

        // Default 404 response
        return response()->view('errors.404', [
            'originalPath' => $path
        ], 404);
    }

    /**
     * Suggest a route based on the path pattern
     */
    private static function suggestRoute($path, $method)
    {
        $suggestions = [];
        
        // Check common patterns
        if (str_contains($path, 'admin')) {
            $suggestions[] = 'admin.dashboard';
        } elseif (str_contains($path, 'facility')) {
            $suggestions[] = 'facility.dashboard';
        } elseif (str_contains($path, 'client')) {
            $suggestions[] = 'client.dashboard';
        } elseif (str_contains($path, 'api')) {
            $suggestions[] = 'api documentation';
        }

        // Check if it's a product or facility path
        if (preg_match('/^products\/\d+$/', $path)) {
            $suggestions[] = 'products.show';
        } elseif (preg_match('/^facilities\/\d+$/', $path)) {
            $suggestions[] = 'facilities.show';
        }

        return !empty($suggestions) ? $suggestions[0] : null;
    }

    /**
     * Get all available routes for debugging
     */
    public static function getAllRoutes()
    {
        $routes = [];
        
        foreach (Route::getRoutes() as $route) {
            $routes[] = [
                'name' => $route->getName(),
                'uri' => $route->uri(),
                'methods' => $route->methods(),
                'action' => $route->getActionName()
            ];
        }
        
        return $routes;
    }

    /**
     * Check if a route exists by name
     */
    public static function routeExists($routeName)
    {
        return Route::has($routeName);
    }

    /**
     * Get route suggestions based on partial name
     */
    public static function getRouteSuggestions($partialName)
    {
        $allRoutes = self::getAllRoutes();
        $suggestions = [];
        
        foreach ($allRoutes as $route) {
            if ($route['name'] && str_contains(strtolower($route['name']), strtolower($partialName))) {
                $suggestions[] = $route['name'];
            }
        }
        
        return $suggestions;
    }
}
