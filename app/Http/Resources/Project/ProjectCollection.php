<?php

namespace App\Http\Resources\Project;

use Illuminate\Http\Request;
use App\Http\Resources\Project\ProjectResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ProjectCollection extends ResourceCollection
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'projects' => ProjectResource::collection($this->collection),
            'meta' => [
                'total' => $this->resource->total(),
                'total_pages' => $this->lastPage(),
                'count' => $this->count(),
                'per_page' => $this->perPage(),
                'current_page' => $this->currentPage(),
                'last_page' => $this->lastPage(),
            ],
            'links' => [
                'self' => $this->url($this->currentPage()),
                'next' => $this->nextPageUrl(),
                'prev' => $this->previousPageUrl(),
            ],
        ];
    }
}
