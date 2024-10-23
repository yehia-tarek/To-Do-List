<?php

namespace App\Http\Resources\SubTask;

use Illuminate\Http\Request;
use App\Http\Resources\SubTask\SubTaskResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class SubTaskCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'tasks' => SubTaskResource::collection($this->collection),
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
