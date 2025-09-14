<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Route;

class RouteHelper
{
    /**
     * Register admin routes with proper middleware and prefix
     */
    public static function registerAdminRoutes(callable $callback): void
    {
        Route::middleware(['web', 'auth', 'role:admin'])
            ->prefix('admin')
            ->name('admin.')
            ->group($callback);
    }

    /**
     * Register facility routes with proper middleware and prefix
     */
    public static function registerFacilityRoutes(callable $callback): void
    {
        Route::middleware(['web', 'auth', 'role:facility'])
            ->prefix('facility')
            ->name('facility.')
            ->group($callback);
    }

    /**
     * Register client routes with proper middleware and prefix
     */
    public static function registerClientRoutes(callable $callback): void
    {
        Route::middleware(['web', 'auth', 'role:client'])
            ->prefix('client')
            ->name('client.')
            ->group($callback);
    }

    /**
     * Register public routes with proper middleware
     */
    public static function registerPublicRoutes(callable $callback): void
    {
        Route::middleware('web')
            ->name('public.')
            ->group($callback);
    }

    /**
     * Register API routes with proper middleware
     */
    public static function registerApiRoutes(callable $callback): void
    {
        Route::middleware('api')
            ->prefix('api')
            ->group($callback);
    }

    /**
     * Get route information for debugging
     */
    public static function getRouteInfo(): array
    {
        $routes = Route::getRoutes();
        $info = [];

        foreach ($routes as $route) {
            $info[] = [
                'method' => implode('|', $route->methods()),
                'uri' => $route->uri(),
                'name' => $route->getName(),
                'action' => $route->getActionName(),
                'middleware' => $route->gatherMiddleware(),
            ];
        }

        return $info;
    }

    /**
     * Check if a route exists
     */
    public static function routeExists(string $name): bool
    {
        return Route::has($name);
    }

    /**
     * Get all routes by middleware
     */
    public static function getRoutesByMiddleware(string $middleware): array
    {
        $routes = Route::getRoutes();
        $filtered = [];

        foreach ($routes as $route) {
            if (in_array($middleware, $route->gatherMiddleware())) {
                $filtered[] = [
                    'method' => implode('|', $route->methods()),
                    'uri' => $route->uri(),
                    'name' => $route->getName(),
                ];
            }
        }

        return $filtered;
    }
}
