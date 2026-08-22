<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\SeedMenuItems;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        SeedMenuItems::class,
    ];

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        if (config('features.auto_reminders')) {
            $schedule->command('reminders:generate-tasks')
                ->dailyAt('08:00')
                ->withoutOverlapping()
                ->onOneServer();
        }
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
