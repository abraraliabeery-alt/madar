<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class OptimizeRoutes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'routes:optimize';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Optimize routes for better performance';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Optimizing routes...');

        // Clear route cache
        Artisan::call('route:clear');
        $this->info('✓ Route cache cleared');

        // Cache routes
        Artisan::call('route:cache');
        $this->info('✓ Routes cached');

        // Clear config cache
        Artisan::call('config:clear');
        $this->info('✓ Config cache cleared');

        // Cache config
        Artisan::call('config:cache');
        $this->info('✓ Config cached');

        // Clear view cache
        Artisan::call('view:clear');
        $this->info('✓ View cache cleared');

        // Cache views
        Artisan::call('view:cache');
        $this->info('✓ Views cached');

        $this->info('Route optimization completed successfully!');
        
        return Command::SUCCESS;
    }
}
