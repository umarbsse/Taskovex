<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Http\Resources\ProjectResource;
use App\Models\ActivityLog;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class ProjectController extends Controller
{
    public function index(): Response
    {
        Gate::authorize('viewAny', Project::class);

        $projects = Project::query()
            ->where('user_id', auth()->id())
            ->withCount([
                'tasks',
                'tasks as completed_tasks_count' => fn ($query) => $query->where('status', Task::STATUS_COMPLETED),
            ])
            ->latest()
            ->get();

        return Inertia::render('Projects/Index', [
            'projects' => ProjectResource::collection($projects)->resolve(),
        ]);
    }

    public function store(StoreProjectRequest $request): RedirectResponse
    {
        Gate::authorize('create', Project::class);

        $project = Project::create([
            ...$request->validated(),
            'user_id' => $request->user()->id,
            'color' => $request->validated('color') ?: '#2563eb',
        ]);

        return Redirect::route('projects.show', $project);
    }

    public function show(Project $project): Response
    {
        Gate::authorize('view', $project);

        $project->load([
            'tasks' => fn ($query) => $query->with(['assignedUser', 'subtasks', 'comments.user', 'attachments'])->latest('updated_at'),
        ])->loadCount([
            'tasks',
            'tasks as completed_tasks_count' => fn ($query) => $query->where('status', Task::STATUS_COMPLETED),
        ]);

        $activityLogs = ActivityLog::query()
            ->with(['user', 'task'])
            ->where('project_id', $project->id)
            ->latest()
            ->limit(20)
            ->get()
            ->map(fn (ActivityLog $log) => [
                'id' => $log->id,
                'action' => $log->action,
                'description' => $log->description,
                'created_at' => $log->created_at?->diffForHumans(),
                'user' => $log->user?->name,
                'task' => $log->task?->title,
            ]);

        return Inertia::render('Projects/Show', [
            'project' => (new ProjectResource($project))->resolve(),
            'users' => User::query()->select('id', 'name', 'email')->orderBy('name')->get(),
            'statuses' => Task::STATUSES,
            'priorities' => Task::PRIORITIES,
            'activityLogs' => $activityLogs,
        ]);
    }

    public function update(UpdateProjectRequest $request, Project $project): RedirectResponse
    {
        Gate::authorize('update', $project);

        $project->update([
            ...$request->validated(),
            'color' => $request->validated('color') ?: '#2563eb',
        ]);

        return Redirect::route('projects.show', $project);
    }

    public function destroy(Project $project): RedirectResponse
    {
        Gate::authorize('delete', $project);

        $project->delete();

        return Redirect::route('projects.index');
    }
}
