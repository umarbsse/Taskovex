<?php

namespace App\Http\Controllers;

use App\Actions\Tasks\AssignTaskAction;
use App\Actions\Tasks\CompleteTaskAction;
use App\Actions\Tasks\CreateTaskAction;
use App\Actions\Tasks\UpdateTaskStatusAction;
use App\Events\TaskCompleted;
use App\Events\UserAssignedToTask;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\ProjectResource;
use App\Http\Resources\TaskResource;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Services\TaskService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class TaskController extends Controller
{
    public function create(Project $project): Response
    {
        Gate::authorize('create', [Task::class, $project]);

        return Inertia::render('Tasks/Create', [
            'project' => (new ProjectResource($project))->resolve(),
            'users' => User::query()->select('id', 'name', 'email')->orderBy('name')->get(),
            'statuses' => Task::STATUSES,
            'priorities' => Task::PRIORITIES,
        ]);
    }

    public function store(StoreTaskRequest $request, Project $project, CreateTaskAction $createTask): RedirectResponse
    {
        Gate::authorize('create', [Task::class, $project]);

        $createTask($project, $request->user(), $request->validated());

        return Redirect::route('projects.show', $project);
    }

    public function edit(Task $task): Response
    {
        Gate::authorize('update', $task);

        $task->load(['project', 'assignedUser', 'subtasks', 'comments.user', 'attachments']);

        return Inertia::render('Tasks/Edit', [
            'task' => (new TaskResource($task))->resolve(),
            'users' => User::query()->select('id', 'name', 'email')->orderBy('name')->get(),
            'statuses' => Task::STATUSES,
            'priorities' => Task::PRIORITIES,
        ]);
    }

    public function update(UpdateTaskRequest $request, Task $task, TaskService $tasks): RedirectResponse
    {
        Gate::authorize('update', $task);

        $previousStatus = $task->status;
        $previousAssignedUserId = $task->assigned_user_id;
        $updatedTask = $tasks->update($task, $request->validated());

        if ($updatedTask->status === Task::STATUS_COMPLETED && $previousStatus !== Task::STATUS_COMPLETED) {
            event(new TaskCompleted($updatedTask, $request->user()));
        }

        if ($updatedTask->assigned_user_id && $updatedTask->assigned_user_id !== $previousAssignedUserId) {
            event(new UserAssignedToTask($updatedTask, $updatedTask->assignedUser, $request->user()));
        }

        return Redirect::route('projects.show', $updatedTask->project);
    }

    public function updateStatus(Request $request, Task $task, UpdateTaskStatusAction $updateStatus): RedirectResponse
    {
        Gate::authorize('updateStatus', $task);

        $validated = $request->validate([
            'status' => ['required', 'string', Rule::in(Task::STATUSES)],
        ]);

        $updateStatus($task, $validated['status'], $request->user());

        return Redirect::back();
    }

    public function assign(Request $request, Task $task, AssignTaskAction $assignTask): RedirectResponse
    {
        Gate::authorize('assign', $task);

        $validated = $request->validate([
            'assigned_user_id' => ['required', 'integer', 'exists:users,id'],
        ]);

        $assignTask($task, User::findOrFail($validated['assigned_user_id']), $request->user());

        return Redirect::back();
    }

    public function complete(Task $task, CompleteTaskAction $completeTask): RedirectResponse
    {
        Gate::authorize('complete', $task);

        $completeTask($task, auth()->user());

        return Redirect::back();
    }

    public function destroy(Task $task): RedirectResponse
    {
        Gate::authorize('delete', $task);

        $project = $task->project;
        $task->delete();

        return Redirect::route('projects.show', $project);
    }
}
