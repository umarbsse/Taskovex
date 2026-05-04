<?php

namespace App\Listeners;

use App\Events\TaskCompleted;
use App\Events\TaskCreated;
use App\Events\UserAssignedToTask;
use App\Services\NotificationService;

class SendTaskNotification
{
    public function __construct(private readonly NotificationService $notifications) {}

    public function handle(TaskCreated|TaskCompleted|UserAssignedToTask $event): void
    {
        if ($event instanceof TaskCreated) {
            $this->notifications->taskCreated($event->task, $event->actor);
        }

        if ($event instanceof TaskCompleted) {
            $this->notifications->taskCompleted($event->task, $event->actor);
        }

        if ($event instanceof UserAssignedToTask) {
            $this->notifications->userAssignedToTask($event->task, $event->assignedUser, $event->actor);
        }
    }
}
