<?php

namespace App\Jobs;

use App\Models\TaskReminder;
use App\Services\NotificationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendTaskReminderJob implements ShouldQueue
{
    use Queueable;

    public function __construct(public readonly TaskReminder $reminder) {}

    public function handle(NotificationService $notifications): void
    {
        $this->reminder->loadMissing('task.project.user', 'task.assignedUser');

        $task = $this->reminder->task;
        $recipient = $task->assignedUser ?? $task->project->user;
        $message = $this->reminder->message ?: "{$task->title} is due soon.";

        $notifications->reminder($task, $recipient, $message);

        $this->reminder->forceFill([
            'sent_at' => now(),
        ])->save();
    }
}
