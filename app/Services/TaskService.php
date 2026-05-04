<?php

namespace App\Services;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;

class TaskService
{
    public function create(Project $project, array $data): Task
    {
        return $project->tasks()->create([
            'assigned_user_id' => $data['assigned_user_id'] ?? null,
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'status' => $data['status'] ?? Task::STATUS_TODO,
            'priority' => $data['priority'] ?? Task::PRIORITY_MEDIUM,
            'due_date' => $data['due_date'] ?? null,
        ]);
    }

    public function update(Task $task, array $data): Task
    {
        $task->fill([
            'assigned_user_id' => $data['assigned_user_id'] ?? null,
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'status' => $data['status'],
            'priority' => $data['priority'],
            'due_date' => $data['due_date'] ?? null,
            'completed_at' => $data['status'] === Task::STATUS_COMPLETED ? ($task->completed_at ?? now()) : null,
        ])->save();

        return $task->refresh();
    }

    public function updateStatus(Task $task, string $status): Task
    {
        $task->forceFill([
            'status' => $status,
            'completed_at' => $status === Task::STATUS_COMPLETED ? ($task->completed_at ?? now()) : null,
        ])->save();

        return $task->refresh();
    }

    public function assign(Task $task, User $user): Task
    {
        $task->forceFill([
            'assigned_user_id' => $user->id,
        ])->save();

        return $task->refresh();
    }

    public function complete(Task $task): Task
    {
        return $this->updateStatus($task, Task::STATUS_COMPLETED);
    }
}
