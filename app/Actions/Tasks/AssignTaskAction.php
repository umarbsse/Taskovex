<?php

namespace App\Actions\Tasks;

use App\Events\UserAssignedToTask;
use App\Models\Task;
use App\Models\User;
use App\Services\TaskService;

class AssignTaskAction
{
    public function __construct(private readonly TaskService $tasks) {}

    public function __invoke(Task $task, User $assignedUser, User $actor): Task
    {
        $task = $this->tasks->assign($task, $assignedUser);

        event(new UserAssignedToTask($task, $assignedUser, $actor));

        return $task;
    }
}
