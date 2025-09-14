<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Route;
use App\Services\RouteFallbackService;

class CheckRoutesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'routes:check {--missing : Show only missing routes} {--suggest : Suggest similar routes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for missing or problematic routes';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking routes...');
        
        $allRoutes = RouteFallbackService::getAllRoutes();
        $missingRoutes = [];
        $problematicRoutes = [];

        // Check for routes that might be missing
        foreach ($allRoutes as $route) {
            if (empty($route['name'])) {
                $problematicRoutes[] = $route;
            }
        }

        // Check for common missing routes
        $commonRoutes = [
            'home',
            'dashboard',
            'admin.dashboard',
            'facility.dashboard',
            'client.dashboard',
            'public.home',
            'login',
            'register',
            'logout'
        ];

        foreach ($commonRoutes as $routeName) {
            if (!Route::has($routeName)) {
                $missingRoutes[] = $routeName;
            }
        }

        $this->info("Total routes found: " . count($allRoutes));
        
        if (!empty($missingRoutes)) {
            $this->warn("Missing common routes:");
            foreach ($missingRoutes as $route) {
                $this->line("  - {$route}");
                
                if ($this->option('suggest')) {
                    $suggestions = RouteFallbackService::getRouteSuggestions($route);
                    if (!empty($suggestions)) {
                        $this->line("    Suggestions: " . implode(', ', $suggestions));
                    }
                }
            }
        }

        if (!empty($problematicRoutes)) {
            $this->warn("Routes without names:");
            foreach ($problematicRoutes as $route) {
                $this->line("  - {$route['uri']} (" . implode(',', $route['methods']) . ")");
            }
        }

        if (empty($missingRoutes) && empty($problematicRoutes)) {
            $this->info("All routes look good!");
        }

        // Show route groups
        $this->info("\nRoute groups:");
        $groups = [
            'admin' => array_filter($allRoutes, fn($r) => str_starts_with($r['name'] ?? '', 'admin.')),
            'facility' => array_filter($allRoutes, fn($r) => str_starts_with($r['name'] ?? '', 'facility.')),
            'client' => array_filter($allRoutes, fn($r) => str_starts_with($r['name'] ?? '', 'client.')),
            'public' => array_filter($allRoutes, fn($r) => str_starts_with($r['name'] ?? '', 'public.')),
            'api' => array_filter($allRoutes, fn($r) => str_starts_with($r['name'] ?? '', 'api.')),
        ];

        foreach ($groups as $group => $routes) {
            $this->line("  {$group}: " . count($routes) . " routes");
        }

        return 0;
    }
}
