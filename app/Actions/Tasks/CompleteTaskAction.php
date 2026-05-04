<?php

namespace App\Actions\Tasks;

use App\Events\TaskCompleted;
use App\Models\Task;
use App\Models\User;
use App\Services\TaskService;

class CompleteTaskAction
{
    public function __construct(private readonly TaskService $tasks) {}

    public function __invoke(Task $task, User $actor): Task
    {
        $wasCompleted = $task->status === Task::STATUS_COMPLETED;
        $task = $this->tasks->complete($task);

        if (! $wasCompleted) {
            event(new TaskCompleted($task, $actor));
        }

        return $task;
    }
}
