<?php
namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;

class TaskRejected extends Notification
{
    protected $task;

    public function __construct($task)
    {
        $this->task = $task;
    }

    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'message' => 'Your task "' . $this->task->title . '" was rejected ❌',
            'task_id' => $this->task->id,
        ]);
    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => 'Your task "' . $this->task->title . '" was rejected ❌',
            'task_id' => $this->task->id,
        ];
    }
}