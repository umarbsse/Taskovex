<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;

class TaskPolicy
{
    public function view(User $user, Task $task): bool
    {
        return $this->ownsProject($user, $task) || $task->assigned_user_id === $user->id;
    }

    public function create(User $user, Project $project): bool
    {
        return $project->user_id === $user->id;
    }

    public function update(User $user, Task $task): bool
    {
        return $this->ownsProject($user, $task) || $task->assigned_user_id === $user->id;
    }

    public function delete(User $user, Task $task): bool
    {
        return $this->ownsProject($user, $task);
    }

    public function assign(User $user, Task $task): bool
    {
        return $this->ownsProject($user, $task);
    }

    public function complete(User $user, Task $task): bool
    {
        return $this->update($user, $task);
    }

    public function updateStatus(User $user, Task $task): bool
    {
        return $this->update($user, $task);
    }

    private function ownsProject(User $user, Task $task): bool
    {
        return $task->project?->user_id === $user->id;
    }
}
