<?php

namespace Database\Factories;

use App\Models\Attachment;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AttachmentFactory extends Factory
{
    protected $model = Attachment::class;

    public function definition(): array
    {
        $name = fake()->word().'.pdf';

        return [
            'task_id' => Task::factory(),
            'user_id' => User::factory(),
            'disk' => 'public',
            'path' => 'attachments/demo/'.$name,
            'original_name' => $name,
            'mime_type' => 'application/pdf',
            'size' => fake()->numberBetween(12000, 300000),
        ];
    }
}
