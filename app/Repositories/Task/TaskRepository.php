<?php

namespace  App\Repositories\Task;

use App\Models\Task;
use App\Repositories\Task\ITaskRepository;

class TaskRepository implements ITaskRepository
{

    public function all($paginate = false, $perPage = 15, $columns = ['*'], $orderBy = 'id', $sort = 'asc')
    {
        $query = Task::select($columns)->orderBy($orderBy, $sort);
        if ($paginate) {
            return $query->paginate($perPage, $columns);
        }
        return $query->get();
    }

    public function getById($id)
    {
        return Task::find($id);
    }

    public function store(array $data)
    {
        return Task::create($data);
    }

    public function update($id, array $data)
    {
        $task = Task::find($id);
        $task->update($data);

        return $task;
    }

    public function delete($id)
    {
        return Task::destroy($id);
    }
}
