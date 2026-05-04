<?php

namespace App\Services;

use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class NotificationService
{
    public function taskCreated(Task $task, User $actor): void
    {
        $task->loadMissing('project', 'assignedUser');

        $this->store($task->assignedUser ?? $task->project->user, 'task_created', [
            'task_id' => $task->id,
            'project_id' => $task->project_id,
            'message' => "{$actor->name} created {$task->title}.",
        ]);
    }

    public function taskCompleted(Task $task, User $actor): void
    {
        $task->loadMissing('project');

        $this->store($task->project->user, 'task_completed', [
            'task_id' => $task->id,
            'project_id' => $task->project_id,
            'message' => "{$actor->name} completed {$task->title}.",
        ]);
    }

    public function userAssignedToTask(Task $task, User $assignedUser, User $actor): void
    {
        $this->store($assignedUser, 'task_assigned', [
            'task_id' => $task->id,
            'project_id' => $task->project_id,
            'message' => "{$actor->name} assigned {$task->title} to you.",
        ]);
    }

    public function reminder(Task $task, User $recipient, string $message): void
    {
        $this->store($recipient, 'task_reminder', [
            'task_id' => $task->id,
            'project_id' => $task->project_id,
            'message' => $message,
        ]);
    }

    public function weeklyReport(User $recipient, array $data): void
    {
        $this->store($recipient, 'weekly_report', $data);
    }

    private function store(User $recipient, string $type, array $data): void
    {
        DB::table('notifications')->insert([
            'id' => (string) Str::uuid(),
            'type' => $type,
            'notifiable_type' => User::class,
            'notifiable_id' => $recipient->id,
            'data' => json_encode($data, JSON_THROW_ON_ERROR),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
