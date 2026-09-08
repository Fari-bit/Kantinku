<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Http\Controllers\Admin\BackupController;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $isEnabled = filter_var(
            env('AUTO_BACKUP_ENABLED', false),
            FILTER_VALIDATE_BOOLEAN
        );

        $interval = (float) env('AUTO_BACKUP_INTERVAL', 3);

        /*
         * Mode demo:
         * interval < 1 menggunakan everyThirtySeconds()
         */
        if ($interval < 1) {
            $schedule->call(function () use ($isEnabled) {
                if ($isEnabled) {
                    app(BackupController::class)
                        ->createAutomaticBackup();
                }
            })->everyThirtySeconds();
        }

        /*
         * Mode normal:
         * interval dihitung dalam jam.
         */
        else {
            $intervalInHours = max(1, (int) $interval);

            $schedule->call(function () use ($isEnabled) {
                if ($isEnabled) {
                    app(BackupController::class)
                        ->createAutomaticBackup();
                }
            })->cron("0 */{$intervalInHours} * * *");
        }
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