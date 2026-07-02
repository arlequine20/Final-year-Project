<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Task;
use App\Models\User;
use App\Notifications\DeadlineReminder;
use Carbon\Carbon;

class SendDeadlineReminders extends Command
{
    protected $signature = 'tasks:deadline-reminder';
    protected $description = 'Send reminders for upcoming task deadlines';

    public function handle()
    {
        $today = Carbon::today();
        $tomorrow = Carbon::tomorrow();

        // Get tasks due tomorrow and not done
        $tasks = Task::whereDate('due_date', $tomorrow)
                     ->where('status', '!=', 'done')
                     ->get();

        foreach ($tasks as $task) {

            if ($task->assigned_to) {
                $user = User::find($task->assigned_to);

                if ($user) {
                    $user->notify(new DeadlineReminder($task));
                }
            }
        }

        return 0;
    }
}