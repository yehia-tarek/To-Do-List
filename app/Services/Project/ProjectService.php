<?php

namespace App\Services\Project;

use App\Services\Project\IProjectService;
use App\Repositories\Project\IProjectRepository;

class ProjectService implements IProjectService
{
    protected $projectRepository;

    public function __construct(IProjectRepository $projectRepository)
    {
        $this->projectRepository = $projectRepository;
    }

    public function all($paginate = false, $perPage = 15, $columns = ['*'], $orderBy = 'id', $sort = 'asc')
    {
        return $this->projectRepository->all($paginate, $perPage, $columns, $orderBy, $sort);
    }

    public function getById($id)
    {
        return $this->projectRepository->getById($id);
    }

    public function store($request)
    {
        return $this->projectRepository->store($request);
    }

    public function update($request, $id)
    {
        return $this->projectRepository->update($request, $id);
    }

    public function delete($id)
    {
        return $this->projectRepository->delete( $id);
    }
}
