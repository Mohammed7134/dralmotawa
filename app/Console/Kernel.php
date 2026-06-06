<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
        Schedule::command('sitemap:generate')->weekly();
        Schedule::command('wisdom:send-daily')->dailyAt('08:00');
        // Re-upload every 47 hours (Gemini deletes files after 48h)
        Schedule::command('gemini:upload-wisdoms')
            ->everySixHours() // runs at 00:00, 06:00, 12:00, 18:00
            ->withoutOverlapping()
            ->runInBackground()
            ->onFailure(function () {
                Log::error('Scheduled Gemini wisdoms upload failed');
            });
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
