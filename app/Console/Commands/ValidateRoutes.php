<?php

namespace App\Console\Commands;

use App\Helpers\RouteHelper;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Route;

class ValidateRoutes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'routes:validate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Validate route configuration and check for issues';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Validating routes...');

        $issues = [];
        $routes = Route::getRoutes();

        // Check for duplicate route names
        $routeNames = [];
        foreach ($routes as $route) {
            $name = $route->getName();
            if ($name) {
                if (isset($routeNames[$name])) {
                    $issues[] = "Duplicate route name: {$name}";
                }
                $routeNames[$name] = true;
            }
        }

        // Check for missing middleware
        foreach ($routes as $route) {
            $middleware = $route->gatherMiddleware();
            $uri = $route->uri();
            
            // Check if admin routes have proper middleware
            if (str_starts_with($uri, 'admin/') && !in_array('role:admin', $middleware)) {
                $issues[] = "Admin route '{$uri}' missing 'role:admin' middleware";
            }
            
            // Check if facility routes have proper middleware
            if (str_starts_with($uri, 'facility/') && !in_array('role:facility', $middleware)) {
                $issues[] = "Facility route '{$uri}' missing 'role:facility' middleware";
            }
            
            // Check if client routes have proper middleware
            if (str_starts_with($uri, 'client/') && !in_array('role:client', $middleware)) {
                $issues[] = "Client route '{$uri}' missing 'role:client' middleware";
            }
        }

        // Check for routes without names
        $unnamedRoutes = [];
        foreach ($routes as $route) {
            if (!$route->getName() && !str_contains($route->uri(), '{')) {
                $unnamedRoutes[] = $route->uri();
            }
        }

        if (!empty($unnamedRoutes)) {
            $issues[] = "Routes without names: " . implode(', ', $unnamedRoutes);
        }

        // Display results
        if (empty($issues)) {
            $this->info('✓ All routes are valid!');
            $this->info("Total routes: " . $routes->count());
        } else {
            $this->error('Found ' . count($issues) . ' issues:');
            foreach ($issues as $issue) {
                $this->error("  - {$issue}");
            }
        }

        // Show route statistics
        $this->newLine();
        $this->info('Route Statistics:');
        $this->info("  Total routes: " . $routes->count());
        $this->info("  Named routes: " . count($routeNames));
        $this->info("  Admin routes: " . count(RouteHelper::getRoutesByMiddleware('role:admin')));
        $this->info("  Facility routes: " . count(RouteHelper::getRoutesByMiddleware('role:facility')));
        $this->info("  Client routes: " . count(RouteHelper::getRoutesByMiddleware('role:client')));

        return empty($issues) ? Command::SUCCESS : Command::FAILURE;
    }
}
