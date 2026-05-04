<?php

namespace App\Jobs;

use App\Models\Task;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class GenerateWeeklyReportJob implements ShouldQueue
{
    use Queueable;

    public function handle(NotificationService $notifications): void
    {
        User::query()
            ->withCount('projects')
            ->chunkById(100, function ($users) use ($notifications): void {
                foreach ($users as $user) {
                    $base = Task::query()->whereHas('project', fn ($query) => $query->where('user_id', $user->id));

                    $notifications->weeklyReport($user, [
                        'message' => 'Your weekly Taskovex report is ready.',
                        'projects_count' => $user->projects_count,
                        'open_tasks_count' => (clone $base)->where('status', '!=', Task::STATUS_COMPLETED)->count(),
                        'completed_tasks_count' => (clone $base)->where('status', Task::STATUS_COMPLETED)->count(),
                    ]);
                }
            });
    }
}
