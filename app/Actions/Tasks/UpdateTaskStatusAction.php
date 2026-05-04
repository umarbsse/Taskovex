<?php

namespace App\Actions\Tasks;

use App\Events\TaskCompleted;
use App\Models\Task;
use App\Models\User;
use App\Services\TaskService;

class UpdateTaskStatusAction
{
    public function __construct(private readonly TaskService $tasks) {}

    public function __invoke(Task $task, string $status, User $actor): Task
    {
        $previousStatus = $task->status;
        $task = $this->tasks->updateStatus($task, $status);

        if ($status === Task::STATUS_COMPLETED && $previousStatus !== Task::STATUS_COMPLETED) {
            event(new TaskCompleted($task, $actor));
        }

        return $task;
    }
}
