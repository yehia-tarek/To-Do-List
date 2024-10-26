<?php

namespace App\Http\Resources\Project;

use App\Http\Resources\Task\TaskResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
{
    private bool $withTasks;

    public function __construct($resource, $withTasks = false)
    {
        parent::__construct($resource);

        $this->withTasks = $withTasks;
    }
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'project_name' => $this->project_name,
            'description' => $this->description,
        ];

        if ($this->withTasks) {
            $data['tasks'] = TaskResource::collection($this->tasks);
        }

        return $data;
    }
}
