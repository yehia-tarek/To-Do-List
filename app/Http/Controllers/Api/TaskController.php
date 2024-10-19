<?php

namespace App\Http\Controllers\Api;

use Exception;
use Illuminate\Http\Request;
use App\Traits\Api\ResponseTrait;
use App\Services\Task\ITaskService;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Http\Resources\Task\TaskResource;
use App\Http\Requests\Api\Task\TaskRequest;
use App\Http\Resources\Task\TaskCollection;
use App\Http\Requests\Api\Task\TaskUpdateRequest;

class TaskController extends Controller
{
    use ResponseTrait;
    protected $taskService;

    public function __construct(ITaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $perPage = $request->get('per_page', 15);

            $cacheKey = "all_tasks_user_{$request->user()}";

            $tasks = Cache::remember($cacheKey, now()->addMinutes(5), function () use ($perPage) {
                return $this->taskService->all(
                    true,
                    $perPage
                );
            });

            return $this->successResponse(
                new TaskCollection($tasks),
                'Tasks retrieved successfully',
                200
            );

        } catch (Exception $e) {
            Log::error('Error retrieving tasks: ' . $e->getMessage());
            return $this->errorResponse('Error retrieving tasks', [], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TaskRequest $request)
    {
        try {
            $data = $request->validated();
            $data['user_id'] = $request->user()->id;

            $task = $this->taskService->store($data);

            Cache::forget("all_tasks_user_{$request->user()}");

            return $this->successResponse(
                new TaskResource($task),
                'Task created successfully',
                201
            );

        } catch (Exception $e) {
            Log::error('Error creating task: ' . $e->getMessage());
            return $this->errorResponse('Error creating task', [], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $task = Cache::remember("task_{$id}", now()->addMinutes(30), function () use ($id) {
                return $this->taskService->getById($id);
            });

            if (!$task) {
                return $this->errorResponse('Task not found', [], 404);
            }

            return $this->successResponse(
                new TaskResource($task),
                'Task retrieved successfully',
                200
            );

        } catch (Exception $e) {
            Log::error('Error retrieving task: ' . $e->getMessage());
            return $this->errorResponse('Error retrieving task', [], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TaskUpdateRequest $request, $id)
    {
        try {
            $task = $this->taskService->update($id, $request->validated());

            if (!$task) {
                return $this->errorResponse('Task not found', 404);
            }

            Cache::forget("task_{$id}");
            Cache::forget("all_tasks_user_{$request->user()}");

            return $this->successResponse(
                new TaskResource($task),
                'Task updated successfully',
                200
            );

        } catch (Exception $e) {
            Log::error('Error updating task: ' . $e->getMessage());
            return $this->errorResponse('Error updating task', [], 500);
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
                return $this->errorResponse('Task not found', 404);
            }

            Cache::forget("task_{$id}");
            Cache::forget("all_tasks_user_{$request->user()}");

            return $this->successResponse(
                [],
                'Task deleted successfully',
                200
            );

        } catch (Exception $e) {
            Log::error('Error deleting task: ' . $e->getMessage());
            return $this->errorResponse('Error deleting task', [], 500);
        }
    }
}
