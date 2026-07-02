<?php
namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;

class ProgressUploaded extends Notification
{
    protected $task; // ✅ REQUIRED

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
            'message' => $this->task->assignee->name .
' uploaded progress on task: ' .
$this->task->title,
            'task_id' => $this->task->id,
        ]);
    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => 'User uploaded progress on a task',
            'task_id' => $this->task->id,
        ];
    }
}