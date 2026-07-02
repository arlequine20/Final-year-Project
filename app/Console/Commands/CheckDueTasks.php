<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Task;
use App\Models\User;
use App\Notifications\DueSoonNotification;
use Carbon\Carbon;


class CheckDueTasks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-due-tasks';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */

public function handle()
{
    $tasks = Task::whereNotNull('due_date')
        ->whereNull('notified_at') // ✅ ONLY not notified
        ->get();

    foreach ($tasks as $task) {

        $due = Carbon::parse($task->due_date);

        if ($due->isPast() || now()->diffInDays($due) <= 1) {

            if ($task->assignee) {
                $task->assignee->notify(new DueSoonNotification($task));
            }

            // ✅ mark as notified
            $task->update([
                'notified_at' => now()
            ]);
        }
    }
}
}
