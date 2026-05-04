<?php

namespace Database\Factories;

use App\Models\Task;
use App\Models\TaskReminder;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskReminderFactory extends Factory
{
    protected $model = TaskReminder::class;

    public function definition(): array
    {
        return [
            'task_id' => Task::factory(),
            'remind_at' => fake()->dateTimeBetween('now', '+14 days'),
            'sent_at' => null,
            'message' => fake()->optional()->sentence(),
        ];
    }
}
