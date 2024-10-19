<?php

namespace App\Services\Task;

use App\Services\Task\ITaskService;
use App\Repositories\Task\ITaskRepository;

class TaskService implements ITaskService
{
    protected $taskRepository;

    public function __construct(ITaskRepository $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    public function all($paginate = false, $perPage = 15, $columns = ['*'], $orderBy = 'id', $sort = 'asc')
    {
        return $this->taskRepository->all($paginate, $perPage, $columns, $orderBy, $sort);
    }

    public function getById($id)
    {
        return $this->taskRepository->getById($id);
    }

    public function store(array $data)
    {
        $data['user_id'] = auth()->user()->id;
        $data['status'] = 'Not Started';
        $data['priority'] = 'Medium';
        
        return $this->taskRepository->store($data);
    }

    public function update($id, array $data)
    {
        return $this->taskRepository->update($id, $data);
    }

    public function delete($id)
    {
        return $this->taskRepository->delete($id);
    }
}
