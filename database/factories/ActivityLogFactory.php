<?php

namespace Database\Factories;

use App\Models\ActivityLog;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ActivityLogFactory extends Factory
{
    protected $model = ActivityLog::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'project_id' => Project::factory(),
            'task_id' => Task::factory(),
            'action' => fake()->randomElement(['task_created', 'task_completed', 'comment_added']),
            'description' => fake()->sentence(),
            'metadata' => null,
        ];
    }
}
