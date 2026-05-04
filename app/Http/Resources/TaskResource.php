<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'project_id' => $this->project_id,
            'assigned_user_id' => $this->assigned_user_id,
            'title' => $this->title,
            'description' => $this->description,
            'status' => $this->status,
            'priority' => $this->priority,
            'due_date' => $this->due_date?->toDateString(),
            'completed_at' => $this->completed_at?->toDateTimeString(),
            'created_at' => $this->created_at?->toDateTimeString(),
            'updated_at' => $this->updated_at?->toDateTimeString(),
            'project' => new ProjectResource($this->whenLoaded('project')),
            'assigned_user' => $this->assignedUser ? [
                'id' => $this->assignedUser->id,
                'name' => $this->assignedUser->name,
                'email' => $this->assignedUser->email,
            ] : null,
            'subtasks' => $this->whenLoaded('subtasks', fn () => $this->subtasks->map(fn ($subtask) => [
                'id' => $subtask->id,
                'title' => $subtask->title,
                'is_completed' => $subtask->is_completed,
            ])->values()),
            'comments' => $this->whenLoaded('comments', fn () => $this->comments->map(fn ($comment) => [
                'id' => $comment->id,
                'body' => $comment->body,
                'created_at' => $comment->created_at?->toDateTimeString(),
                'user' => [
                    'id' => $comment->user->id,
                    'name' => $comment->user->name,
                ],
            ])->values()),
            'attachments' => $this->whenLoaded('attachments', fn () => $this->attachments->map(fn ($attachment) => [
                'id' => $attachment->id,
                'original_name' => $attachment->original_name,
                'mime_type' => $attachment->mime_type,
                'size' => $attachment->size,
            ])->values()),
        ];
    }
}
