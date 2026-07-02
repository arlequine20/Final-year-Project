<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;

class TaskAssigned extends Notification
{
    protected $task; // ✅ ADD THIS

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
            'message' => 'You have been assigned a new task: ' . $this->task->title,
            'task_id' => $this->task->id,
        ]);
    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => 'You have been assigned a new task: ' . $this->task->title,
            'task_id' => $this->task->id,
        ];
    }
}