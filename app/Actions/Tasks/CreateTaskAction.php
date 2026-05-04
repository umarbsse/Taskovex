<?php

namespace App\Actions\Tasks;

use App\Events\TaskCreated;
use App\Events\UserAssignedToTask;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Services\TaskService;

class CreateTaskAction
{
    public function __construct(private readonly TaskService $tasks) {}

    public function __invoke(Project $project, User $actor, array $data): Task
    {
        $task = $this->tasks->create($project, $data);
        $task->loadMissing('assignedUser', 'project');

        event(new TaskCreated($task, $actor));

        if ($task->assignedUser) {
            event(new UserAssignedToTask($task, $task->assignedUser, $actor));
        }

        return $task;
    }
}
