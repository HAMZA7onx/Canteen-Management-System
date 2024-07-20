<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Jobs\SendAdminReports;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->job(new SendAdminReports('daily'))->dailyAt('16:36');
        $schedule->job(new SendAdminReports('weekly'))->weekly()->sundays()->at('11:59');
        $schedule->job(new SendAdminReports('monthly'))->monthly()->lastDayOfMonth()->at('11:59');
        $schedule->job(new SendAdminReports('yearly'))->cron('59 23 31 12 *');
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
