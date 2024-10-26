<?php

namespace App\Http\Controllers\Api;

use Exception;
use Illuminate\Http\Request;
use App\Traits\Api\ResponseTrait;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Http\Requests\Api\ProjectRequest;
use App\Services\Project\IProjectService;
use App\Http\Resources\Project\ProjectResource;
use App\Http\Resources\Project\ProjectCollection;

class ProjectController extends Controller
{
    use ResponseTrait;
    protected $projectService;

    public function __construct(IProjectService $projectService)
    {
        $this->projectService = $projectService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $perPage = $request->get('per_page', 15);

            $cacheKey = "all_projects_user_{$request->user()}";

            $projects = Cache::remember($cacheKey, now()->addMinutes(5), function () use ($perPage) {
                return $this->projectService->all(
                    true,
                    $perPage,
                    ['id', 'user_id', 'project_name', 'description'],
                );
            });

            return $this->successResponse(
                new ProjectCollection($projects),
                'Projects retrieved successfully',
                200,
            );

        } catch (Exception $e) {
            Log::error('Error retrieving projects: ' . $e->getMessage());
            return $this->errorResponse('An error occurred while retrieving projects', 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProjectRequest $request)
    {
        try {
            $data = $request->validated();
            $data['user_id'] = $request->user()->id;

            $project = $this->projectService->store($data);

            Cache::forget("all_projects_user_{$request->user()}");

            return $this->successResponse(
                new ProjectResource($project),
                'Project created successfully',
                201
            );
        } catch (Exception $e) {
            Log::error('Error creating project: ' . $e->getMessage());
            return $this->errorResponse('An error occurred while creating the project', [], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $project = Cache::remember("project_{$id}", now()->addMinutes(30), function () use ($id) {
                return $this->projectService->getById($id);
            });

            if (!$project) {
                return $this->errorResponse('Project not found', 404);
            }

            return $this->successResponse(
                new ProjectResource($project, true),
                'Project retrieved successfully'
            );
        } catch (Exception $e) {
            Log::error('Error retrieving project: ' . $e->getMessage());
            return $this->errorResponse('An error occurred while retrieving the project', [], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProjectRequest $request, $id)
    {
        try {
            $project = $this->projectService->update($request->validated(), $id);

            if (!$project) {
                return $this->errorResponse('Project not found', 404);
            }

            Cache::forget("project_{$id}");
            Cache::forget("all_projects_user_{$request->user()}");

            return $this->successResponse(
                new ProjectResource($project),
                'Project updated successfully'
            );
        } catch (Exception $e) {
            Log::error('Error updating project: ' . $e->getMessage());
            return $this->errorResponse('An error occurred while updating the project', [], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id)
    {
        try {
            $status = $this->projectService->delete($id);

            if (!$status) {
                return $this->errorResponse('Project not found', 404);
            }

            Cache::forget("project_{$id}");
            Cache::forget("all_projects_user_{$request->user()}");

            return $this->successResponse(
                [],
                'Project deleted successfully'
            );
        } catch (Exception $e) {
            Log::error('Error deleting project: ' . $e->getMessage());
            return $this->errorResponse('An error occurred while deleting the project', [], 500);
        }
    }
}
