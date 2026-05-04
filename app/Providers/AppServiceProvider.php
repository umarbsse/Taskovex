<?php

namespace App\Providers;

use App\Events\TaskCompleted;
use App\Events\TaskCreated;
use App\Events\UserAssignedToTask;
use App\Listeners\LogTaskActivity;
use App\Listeners\SendTaskNotification;
use App\Models\Project;
use App\Models\Task;
use App\Policies\ProjectPolicy;
use App\Policies\TaskPolicy;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        $this->configureUrlRoot();

        Gate::policy(Project::class, ProjectPolicy::class);
        Gate::policy(Task::class, TaskPolicy::class);

        foreach ([TaskCreated::class, TaskCompleted::class, UserAssignedToTask::class] as $event) {
            Event::listen($event, SendTaskNotification::class);
            Event::listen($event, LogTaskActivity::class);
        }

        Vite::prefetch(concurrency: 3);
    }

    private function configureUrlRoot(): void
    {
        $appUrl = config('app.url');
        $path = is_string($appUrl) ? parse_url($appUrl, PHP_URL_PATH) : null;

        if (! $this->app->runningUnitTests() && is_string($appUrl) && $path && $path !== '/') {
            URL::forceRootUrl(rtrim($appUrl, '/'));
        }
    }
}
