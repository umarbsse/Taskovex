<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    protected $model = Task::class;

    public function definition(): array
    {
        return [
            'project_id' => Project::factory(),
            'assigned_user_id' => User::factory(),
            'title' => fake()->sentence(4),
            'description' => fake()->paragraph(),
            'status' => fake()->randomElement(Task::STATUSES),
            'priority' => fake()->randomElement(Task::PRIORITIES),
            'due_date' => fake()->optional()->dateTimeBetween('now', '+30 days'),
            'completed_at' => null,
        ];
    }

    public function completed(): static
    {
        return $this->state(fn () => [
            'status' => Task::STATUS_COMPLETED,
            'completed_at' => now(),
        ]);
    }
}
