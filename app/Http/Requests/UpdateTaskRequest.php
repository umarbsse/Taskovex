<?php

namespace App\Http\Requests;

use App\Models\Task;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:180'],
            'description' => ['nullable', 'string', 'max:8000'],
            'status' => ['required', 'string', Rule::in(Task::STATUSES)],
            'priority' => ['required', 'string', Rule::in(Task::PRIORITIES)],
            'due_date' => ['nullable', 'date'],
            'assigned_user_id' => ['nullable', 'integer', 'exists:users,id'],
        ];
    }
}
