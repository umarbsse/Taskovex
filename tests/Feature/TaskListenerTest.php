<?php

namespace Tests\Feature;

use App\Events\TaskCompleted;
use App\Events\TaskCreated;
use App\Listeners\LogTaskActivity;
use App\Listeners\SendTaskNotification;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskListenerTest extends TestCase
{
    use RefreshDatabase;

    public function test_task_listeners_create_notifications_and_activity_logs(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create([
            'user_id' => $user->id,
        ]);
        $task = Task::factory()->create([
            'project_id' => $project->id,
            'assigned_user_id' => $user->id,
        ]);

        app(SendTaskNotification::class)->handle(new TaskCreated($task, $user));
        app(LogTaskActivity::class)->handle(new TaskCompleted($task, $user));

        $this->assertDatabaseHas('notifications', [
            'type' => 'task_created',
            'notifiable_id' => $user->id,
        ]);

        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $user->id,
            'project_id' => $project->id,
            'task_id' => $task->id,
            'action' => 'task_completed',
        ]);
    }
}
