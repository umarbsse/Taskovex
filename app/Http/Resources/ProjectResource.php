<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'color' => $this->color,
            'created_at' => $this->created_at?->toDateTimeString(),
            'updated_at' => $this->updated_at?->toDateTimeString(),
            'tasks_count' => $this->tasks_count ?? $this->tasks()->count(),
            'completed_tasks_count' => $this->completed_tasks_count ?? $this->tasks()->where('status', 'completed')->count(),
            'tasks' => TaskResource::collection($this->whenLoaded('tasks')),
        ];
    }
}
