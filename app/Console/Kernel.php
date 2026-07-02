<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    // ✅ Register your custom commands
    protected $commands = [
        \App\Console\Commands\CheckDueTasks::class,
    ];

    // ✅ Schedule commands here
    protected function schedule(Schedule $schedule)
    {
        // 🔔 Check due tasks every minute
        $schedule->command('tasks:check-due')->everyMinute();

        // (Optional) Daily reminder
        // $schedule->command('tasks:deadline-reminder')->daily();
    }

    // ✅ Load commands automatically
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}