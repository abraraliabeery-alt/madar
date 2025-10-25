<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Route;

class GenerateRouteDocs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'routes:docs {--output=docs/routes.md}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate route documentation';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $outputFile = $this->option('output');
        $this->info("Generating route documentation to: {$outputFile}");

        $routes = Route::getRoutes();
        $groupedRoutes = [];

        // Group routes by prefix
        foreach ($routes as $route) {
            $uri = $route->uri();
            $prefix = $this->getRoutePrefix($uri);
            
            if (!isset($groupedRoutes[$prefix])) {
                $groupedRoutes[$prefix] = [];
            }
            
            $groupedRoutes[$prefix][] = [
                'method' => implode('|', $route->methods()),
                'uri' => $uri,
                'name' => $route->getName(),
                'action' => $route->getActionName(),
                'middleware' => $route->gatherMiddleware(),
            ];
        }

        // Generate markdown documentation
        $markdown = $this->generateMarkdown($groupedRoutes);
        
        // Ensure directory exists
        $directory = dirname($outputFile);
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }
        
        file_put_contents($outputFile, $markdown);
        
        $this->info("✓ Route documentation generated successfully!");
        $this->info("File: {$outputFile}");
        
        return Command::SUCCESS;
    }

    /**
     * Get route prefix from URI
     */
    private function getRoutePrefix(string $uri): string
    {
        $segments = explode('/', $uri);
        return $segments[0] ?: 'root';
    }

    /**
     * Generate markdown documentation
     */
    private function generateMarkdown(array $groupedRoutes): string
    {
        $markdown = "# Route Documentation\n\n";
        $markdown .= "Generated on: " . now()->format('Y-m-d H:i:s') . "\n\n";
        $markdown .= "## Overview\n\n";
        $markdown .= "This document contains all registered routes in the application.\n\n";

        foreach ($groupedRoutes as $prefix => $routes) {
            $markdown .= "## {$prefix} Routes\n\n";
            $markdown .= "| Method | URI | Name | Action | Middleware |\n";
            $markdown .= "|--------|-----|------|--------|------------|\n";

            foreach ($routes as $route) {
                $method = $route['method'];
                $uri = $route['uri'];
                $name = $route['name'] ?: '-';
                $action = $route['action'];
                $middleware = implode(', ', $route['middleware']);

                $markdown .= "| {$method} | `{$uri}` | `{$name}` | `{$action}` | `{$middleware}` |\n";
            }

            $markdown .= "\n";
        }

        return $markdown;
    }
}
