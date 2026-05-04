<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProjectResource;
use App\Http\Resources\TaskResource;
use App\Models\ActivityLog;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(Request $request): Response
    {
        $user = $request->user();

        $taskQuery = Task::query()
            ->with(['project', 'assignedUser'])
            ->where(function ($query) use ($user): void {
                $query
                    ->whereHas('project', fn ($project) => $project->where('user_id', $user->id))
                    ->orWhere('assigned_user_id', $user->id);
            });

        $tasks = (clone $taskQuery)
            ->latest('updated_at')
            ->get();

        $projects = Project::query()
            ->where('user_id', $user->id)
            ->withCount([
                'tasks',
                'tasks as completed_tasks_count' => fn ($query) => $query->where('status', Task::STATUS_COMPLETED),
            ])
            ->latest()
            ->limit(6)
            ->get();

        $activityLogs = ActivityLog::query()
            ->with(['user', 'project', 'task'])
            ->where(function ($query) use ($user): void {
                $query
                    ->where('user_id', $user->id)
                    ->orWhereHas('project', fn ($project) => $project->where('user_id', $user->id));
            })
            ->latest()
            ->limit(12)
            ->get()
            ->map(fn (ActivityLog $log) => [
                'id' => $log->id,
                'action' => $log->action,
                'description' => $log->description,
                'created_at' => $log->created_at?->diffForHumans(),
                'user' => $log->user?->name,
                'project' => $log->project?->name,
                'task' => $log->task?->title,
            ]);

        return Inertia::render('Dashboard', [
            'stats' => [
                'projects' => $projects->count(),
                'open_tasks' => $tasks->where('status', '!=', Task::STATUS_COMPLETED)->count(),
                'completed_tasks' => $tasks->where('status', Task::STATUS_COMPLETED)->count(),
                'due_soon' => $tasks
                    ->filter(fn (Task $task) => $task->due_date && $task->due_date->between(now(), now()->addDays(7)))
                    ->count(),
            ],
            'projects' => ProjectResource::collection($projects)->resolve(),
            'tasks' => TaskResource::collection($tasks)->resolve(),
            'activityLogs' => $activityLogs,
        ]);
    }
}
