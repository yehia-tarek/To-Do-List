<?php

namespace App\Services\SubTask;

use App\Services\SubTask\ISubTaskService;
use App\Repositories\SubTask\ISubTaskRepository;

class SubTaskService implements ISubTaskService
{
    protected $subTaskRepository;

    public function __construct(ISubTaskRepository $subTaskRepository)
    {
        $this->subTaskRepository = $subTaskRepository;
    }

    public function allSubTasksByTaskId($taskId, $paginate = false, $perPage = 15, $columns = ['*'], $orderBy = 'id', $sort = 'asc')
    {
        return $this->subTaskRepository->allSubTasksByTaskId($taskId, $paginate, $perPage, $columns, $orderBy, $sort);
    }

    public function getById($id)
    {
        return $this->subTaskRepository->getById($id);
    }

    public function store(array $data)
    {
        $data['user_id'] = auth()->user()->id;
        $data['status'] = 'Not Started';
        $data['priority'] = 'Medium';

        return $this->subTaskRepository->store($data);
    }

    public function update($id, array $data)
    {
        return $this->subTaskRepository->update($id, $data);
    }

    public function delete($id)
    {
        return $this->subTaskRepository->delete($id);
    }
}
