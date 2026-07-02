<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;

class ProgressViewed extends Notification
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
            'message' => 'Admin viewed your progress on task: ' . $this->task->title,
            'task_id' => $this->task->id,
        ]);
    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => 'Admin viewed your progress on task: ' . $this->task->title,
            'task_id' => $this->task->id,
        ];
    }
}