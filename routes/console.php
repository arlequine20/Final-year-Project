<?php

use Illuminate\Support\Facades\Schedule;
use App\Models\Task;
use App\Models\User;
use App\Notifications\DueSoonNotification;
use Carbon\Carbon;

// 🔔 COMMAND
Artisan::command('tasks:check-due', function () {

    $tasks = Task::whereNotNull('due_date')
        ->where('status', '!=', 'done')
        ->get();

    foreach ($tasks as $task) {

        $daysLeft = Carbon::now()->diffInDays($task->due_date, false);

        if ($daysLeft <= 1) {

            if ($task->assigned_to) {
                $user = User::find($task->assigned_to);

                if ($user) {
                    $user->notify(new DueSoonNotification($task));
                }
            }
        }
    }

    $this->info('Checked due tasks');
});

// ⏰ SCHEDULER (RUNS AUTOMATICALLY)
Schedule::command('tasks:check-due')->everyMinute();