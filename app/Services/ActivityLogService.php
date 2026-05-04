<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\Task;
use App\Models\User;

class ActivityLogService
{
    public function taskCreated(Task $task, User $actor): ActivityLog
    {
        return $this->record($actor, $task, 'task_created', "Created {$task->title}.");
    }

    public function taskCompleted(Task $task, User $actor): ActivityLog
    {
        return $this->record($actor, $task, 'task_completed', "Completed {$task->title}.");
    }

    public function userAssignedToTask(Task $task, User $assignedUser, User $actor): ActivityLog
    {
        return $this->record($actor, $task, 'task_assigned', "Assigned {$task->title} to {$assignedUser->name}.", [
            'assigned_user_id' => $assignedUser->id,
        ]);
    }

    public function commentAdded(Task $task, User $actor): ActivityLog
    {
        return $this->record($actor, $task, 'comment_added', "Commented on {$task->title}.");
    }

    private function record(User $actor, Task $task, string $action, string $description, array $metadata = []): ActivityLog
    {
        return ActivityLog::create([
            'user_id' => $actor->id,
            'project_id' => $task->project_id,
            'task_id' => $task->id,
            'action' => $action,
            'description' => $description,
            'metadata' => $metadata ?: null,
        ]);
    }
}
