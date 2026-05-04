<?php

namespace Database\Seeders;

use App\Models\ActivityLog;
use App\Models\Comment;
use App\Models\Project;
use App\Models\SubTask;
use App\Models\Task;
use App\Models\TaskReminder;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $owner = User::factory()->create([
            'name' => 'Taskovex Demo',
            'email' => 'demo@taskovex.test',
        ]);

        $team = User::factory(4)->create();
        $users = $team->push($owner);

        $projects = collect([
            ['name' => 'Product Launch', 'color' => '#2563eb'],
            ['name' => 'Client Portal', 'color' => '#059669'],
            ['name' => 'Operations Workflow', 'color' => '#7c3aed'],
        ])->map(fn (array $project) => Project::factory()->create([
            'user_id' => $owner->id,
            'name' => $project['name'],
            'description' => fake()->paragraph(),
            'color' => $project['color'],
        ]));

        foreach ($projects as $project) {
            foreach (Task::STATUSES as $status) {
                Task::factory(3)->create([
                    'project_id' => $project->id,
                    'assigned_user_id' => $users->random()->id,
                    'status' => $status,
                    'completed_at' => $status === Task::STATUS_COMPLETED ? now()->subDays(rand(1, 10)) : null,
                ])->each(function (Task $task) use ($users, $owner): void {
                    SubTask::factory(rand(2, 4))->create([
                        'task_id' => $task->id,
                    ]);

                    Comment::factory(rand(1, 3))->create([
                        'task_id' => $task->id,
                        'user_id' => $users->random()->id,
                    ]);

                    TaskReminder::factory()->create([
                        'task_id' => $task->id,
                        'remind_at' => now()->addDays(rand(1, 10)),
                    ]);

                    ActivityLog::factory()->create([
                        'user_id' => $owner->id,
                        'project_id' => $task->project_id,
                        'task_id' => $task->id,
                        'action' => 'task_created',
                        'description' => "Created {$task->title}.",
                    ]);
                });
            }
        }
    }
}
