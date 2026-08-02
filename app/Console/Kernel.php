<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('sitemap:generate')->dailyAt('02:00');
        $schedule->command('classifieds:expire')->dailyAt('03:00');

        // Backfill coordinates for listings submitted since the last run, so new
        // listings get a real map pin instead of the area-centroid fallback.
        // Only touches listings missing lat/lng — owner-pinned locations are safe.
        $schedule->command('listings:geocode')->dailyAt('04:00')->withoutOverlapping();
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
