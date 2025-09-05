<?php

namespace App\Console;

use App\Jobs\DeleteExpiredOrders;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // in here i need to call my Job class created By me
        // $schedule->job(new DeleteExpiredOrders())->daily(); // execute this job everyDay
        $schedule->job(new DeleteExpiredOrders())->everyTwoMinutes(); // execute this job everyDay
        //todo طبعا هناك العديييد من الدوال الجميلة لوقت تنفيذ هذه العمليات
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
