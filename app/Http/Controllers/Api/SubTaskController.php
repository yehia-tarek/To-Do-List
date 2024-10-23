<?php

namespace App\Http\Controllers\Api;

use Exception;
use Illuminate\Http\Request;
use App\Traits\Api\ResponseTrait;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Services\SubTask\ISubTaskService;
use App\Http\Resources\SubTask\SubTaskResource;
use App\Http\Requests\Api\SubTask\SubTaskRequest;
use App\Http\Resources\SubTask\SubTaskCollection;
use App\Http\Requests\Api\SubTask\SubTaskUpdateRequest;

class SubTaskController extends Controller
{
    use ResponseTrait;
    protected $taskService;

    public function __construct(ISubTaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $id)
    {
        try {
            $perPage = $request->get('per_page', 15);

            $cacheKey = "all_sub_tasks_user_{$request->user()}";

            $tasks = Cache::remember($cacheKey, now()->addMinutes(5), function () use ($perPage, $id) {
                return $this->taskService->all(
                    $id,
                    true,
                    $perPage
                );
            });

            return $this->successResponse(
                new SubTaskCollection($tasks),
                'Sub Tasks retrieved successfully',
                200
            );

        } catch (Exception $e) {
            Log::error('Error retrieving sub tasks: ' . $e->getMessage());
            return $this->errorResponse('Error retrieving sub tasks', [], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SubTaskRequest $request)
    {
        try {
            $data = $request->validated();
            $data['user_id'] = $request->user()->id;

            $task = $this->taskService->store($data);

            Cache::forget("all_sub_tasks_user_{$request->user()}");

            return $this->successResponse(
                new SubTaskResource($task),
                'Sub Task created successfully',
                201
            );

        } catch (Exception $e) {
            Log::error('Error creating sub task: ' . $e->getMessage());
            return $this->errorResponse('Error creating sub task', [], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $task = Cache::remember("sub_task_{$id}", now()->addMinutes(30), function () use ($id) {
                return $this->taskService->getById($id);
            });

            if (!$task) {
                return $this->errorResponse('Sub Task not found', [], 404);
            }

            return $this->successResponse(
                new SubTaskResource($task),
                'Sub Task retrieved successfully',
                200
            );

        } catch (Exception $e) {
            Log::error('Error retrieving sub task: ' . $e->getMessage());
            return $this->errorResponse('Error retrieving sub task', [], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SubTaskUpdateRequest $request, $id)
    {
        try {
            $task = $this->taskService->update($id, $request->validated());

            if (!$task) {
                return $this->errorResponse('Sub Task not found', 404);
            }

            Cache::forget("task_{$id}");
            Cache::forget("all_tasks_user_{$request->user()}");

            return $this->successResponse(
                new SubTaskResource($task),
                'Sub Task updated successfully',
                200
            );

        } catch (Exception $e) {
            Log::error('Error updating sub task: ' . $e->getMessage());
            return $this->errorResponse('Error updating subtask', [], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id, Request $request)
    {
        try {
            $task = $this->taskService->delete($id);

            if (!$task) {
                return $this->errorResponse('Sub Task not found', 404);
            }

            Cache::forget("task_{$id}");
            Cache::forget("all_sub_tasks_user_{$request->user()}");

            return $this->successResponse(
                [],
                'Sub Task deleted successfully',
                200
            );

        } catch (Exception $e) {
            Log::error('Error deleting sub task: ' . $e->getMessage());
            return $this->errorResponse('Error deleting sub task', [], 500);
        }
    }
}
