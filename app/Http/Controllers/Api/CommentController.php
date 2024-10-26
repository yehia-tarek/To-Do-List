<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\Comment;
use Illuminate\Http\Request;
use App\Traits\Api\ResponseTrait;
use App\Services\Task\ITaskService;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CommentRequest;
use App\Services\Comment\ICommentService;
use App\Http\Resources\Comment\CommentResource;
use App\Http\Resources\Comment\CommentCollection;

class CommentController extends Controller
{
    use ResponseTrait;

    protected $commentService;
    protected $taskService;

    public function __construct(ICommentService $commentService, ITaskService $taskService)
    {
        $this->commentService = $commentService;
        $this->taskService = $taskService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $id)
    {
        try {
            $task = $this->taskService->getById($id);

            if (!$task) {
                return $this->errorResponse('Task not found', [], 404);
            }

            $perPage = $request->get('per_page', 15);

            $comments = $this->commentService->allCommentsByTaskId($id, true, $perPage);

            return $this->successResponse(
                new CommentCollection($comments),
                'Comments retrieved successfully'
            );
        } catch (Exception $e) {
            Log::error('Error retrieving task: ' . $e->getMessage() . ' File: ' . $e->getFile() . ' Line: ' . $e->getLine());
            return $this->errorResponse('Error retrieving task', [], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CommentRequest $request)
    {
        try {
            $comment = $this->commentService->store($request->validated());

            return $this->successResponse(
                new CommentResource($comment),
                'Comment created successfully',
                201
            );
        } catch (Exception $e) {
            Log::error('Error creating comment: ' . $e->getMessage() . ' File: ' . $e->getFile() . ' Line: ' . $e->getLine());
            return $this->errorResponse('An error occurred while creating the comment', [], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $comment = $this->commentService->getById($id);

            if (!$comment) {
                return $this->errorResponse('Comment not found', [], 404);
            }

            return $this->successResponse(
                new CommentResource($comment),
                'Comment retrieved successfully'
            );

        } catch (Exception $e) {
            Log::error('Error retrieving comment: ' . $e->getMessage() . ' File: ' . $e->getFile() . ' Line: ' . $e->getLine());
            return $this->errorResponse('An error occurred while retrieving the comment', [], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CommentRequest $request, $id)
    {
        try {
            $comment = $this->commentService->update($id, $request->validated());

            if (!$comment) {
                return $this->errorResponse('Comment not found', [], 404);
            }

            return $this->successResponse(
                new CommentResource($comment),
                'Comment updated successfully'
            );

        } catch (Exception $e) {
            Log::error('Error updating comment: ' . $e->getMessage() . ' File: ' . $e->getFile() . ' Line: ' . $e->getLine());
            return $this->errorResponse('An error occurred while updating the comment', [], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $status = $this->commentService->delete($id);

            if (!$status) {
                return $this->errorResponse('Comment not found', [], 404);
            }

            return $this->successResponse(
                [],
                'Comment deleted successfully'
            );

        } catch (Exception $e) {
            Log::error('Error deleting comment: ' . $e->getMessage() . ' File: ' . $e->getFile() . ' Line: ' . $e->getLine());
            return $this->errorResponse('An error occurred while deleting the comment', [], 500);
        }
    }
}
