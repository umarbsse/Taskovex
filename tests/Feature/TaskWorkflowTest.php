<?php

namespace Tests\Feature;

use App\Events\TaskCompleted;
use App\Events\TaskCreated;
use App\Events\UserAssignedToTask;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class TaskWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_task_creation_assignment_and_completion_dispatch_events(): void
    {
        Event::fake([
            TaskCreated::class,
            TaskCompleted::class,
            UserAssignedToTask::class,
        ]);

        $owner = User::factory()->create();
        $assignee = User::factory()->create();
        $project = Project::factory()->create([
            'user_id' => $owner->id,
        ]);

        $this->actingAs($owner)->post(route('projects.tasks.store', $project), [
            'title' => 'Prepare launch checklist',
            'description' => 'Confirm launch readiness across teams.',
            'status' => Task::STATUS_TODO,
            'priority' => Task::PRIORITY_HIGH,
            'due_date' => now()->addWeek()->toDateString(),
            'assigned_user_id' => $assignee->id,
        ])->assertRedirect(route('projects.show', $project));

        $task = Task::query()->firstOrFail();

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'project_id' => $project->id,
            'assigned_user_id' => $assignee->id,
            'status' => Task::STATUS_TODO,
        ]);

        Event::assertDispatched(TaskCreated::class);
        Event::assertDispatched(UserAssignedToTask::class);

        $this->actingAs($owner)->patch(route('tasks.status', $task), [
            'status' => Task::STATUS_COMPLETED,
        ])->assertRedirect();

        Event::assertDispatched(TaskCompleted::class);
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'status' => Task::STATUS_COMPLETED,
        ]);
        $this->assertNotNull($task->refresh()->completed_at);
    }

    public function test_assigned_user_can_update_task_status(): void
    {
        $owner = User::factory()->create();
        $assignee = User::factory()->create();
        $project = Project::factory()->create([
            'user_id' => $owner->id,
        ]);
        $task = Task::factory()->create([
            'project_id' => $project->id,
            'assigned_user_id' => $assignee->id,
            'status' => Task::STATUS_TODO,
        ]);

        $this->actingAs($assignee)->patch(route('tasks.status', $task), [
            'status' => Task::STATUS_IN_PROGRESS,
        ])->assertRedirect();

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'status' => Task::STATUS_IN_PROGRESS,
        ]);
    }
}
