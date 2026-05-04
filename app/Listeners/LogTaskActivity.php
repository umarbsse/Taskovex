<?php

namespace App\Listeners;

use App\Events\TaskCompleted;
use App\Events\TaskCreated;
use App\Events\UserAssignedToTask;
use App\Services\ActivityLogService;

class LogTaskActivity
{
    public function __construct(private readonly ActivityLogService $activity) {}

    public function handle(TaskCreated|TaskCompleted|UserAssignedToTask $event): void
    {
        if ($event instanceof TaskCreated) {
            $this->activity->taskCreated($event->task, $event->actor);
        }

        if ($event instanceof TaskCompleted) {
            $this->activity->taskCompleted($event->task, $event->actor);
        }

        if ($event instanceof UserAssignedToTask) {
            $this->activity->userAssignedToTask($event->task, $event->assignedUser, $event->actor);
        }
    }
}
