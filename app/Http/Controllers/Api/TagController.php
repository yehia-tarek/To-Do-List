<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\Tag;
use Illuminate\Http\Request;
use App\Services\Tag\ITagService;
use App\Traits\Api\ResponseTrait;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\TagRequest;
use App\Http\Resources\Tag\TagResource;
use App\Http\Resources\Tag\TagCollection;

class TagController extends Controller
{
    use ResponseTrait;

    protected $tagService;

    public function __construct(ITagService $tagService)
    {
        $this->tagService = $tagService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $perPage = $request->get('per_page', 15);

            $tags = $this->tagService->all(true, $perPage);

            return $this->successResponse(
                new TagCollection($tags),
                'Tags retrieved successfully',
                200
            );

        } catch (Exception $e) {
            Log::error('Error retrieving tags: ' . $e->getMessage() . ' File: ' . $e->getFile() . ' Line: ' . $e->getLine());
            return $this->errorResponse('Error retrieving tags', [], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TagRequest $request)
    {
        try {
            $tag = $this->tagService->store($request->validated());

            return $this->successResponse(
                new TagResource($tag),
                'Tag created successfully',
                200
            );
        } catch (Exception $e) {
            Log::error('Error creating tag: ' . $e->getMessage() . ' File: ' . $e->getFile() . ' Line: ' . $e->getLine());
            return $this->errorResponse('Error creating tag', [], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $tag = $this->tagService->getById($id);

            if (!$tag) {
                return $this->errorResponse('Tag not found', [], 404);
            }

            return $this->successResponse(
                new TagResource($tag),
                'Tag retrieved successfully',
                200
            );
        } catch (Exception $e) {
            Log::error('Error retrieving tag: ' . $e->getMessage() . ' File: ' . $e->getFile() . ' Line: ' . $e->getLine());
            return $this->errorResponse('Error retrieving tag', [], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TagRequest $request, $id)
    {
        try {
            $tag = $this->tagService->update($id, $request->validated());

            if (!$tag) {
                return $this->errorResponse('Tag not found', [], 404);
            }

            return $this->successResponse(
                new TagResource($tag),
                'Tag updated successfully',
                200
            );
        } catch (Exception $e) {
            Log::error('Error updating tag: ' . $e->getMessage() . ' File: ' . $e->getFile() . ' Line: ' . $e->getLine());
            return $this->errorResponse('Error updating tag', [], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $tag = $this->tagService->delete($id);

            if (!$tag) {
                return $this->errorResponse('Tag not found', [], 404);
            }

            return $this->successResponse(
                [],
                'Tag deleted successfully',
                200
            );
        } catch (Exception $e) {
            Log::error('Error deleting tag: ' . $e->getMessage() . ' File: ' . $e->getFile() . ' Line: ' . $e->getLine());
            return $this->errorResponse('Error deleting tag', [], 500);
        }
    }
}
