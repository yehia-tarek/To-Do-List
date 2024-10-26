<?php

namespace App\Repositories\SubTask;

use App\Models\SubTask;
use App\Repositories\SubTask\ISubTaskRepository;

class SubTaskRepository implements ISubTaskRepository
{

    public function allSubTasksByTaskId($taskId, $paginate = false, $perPage = 15, $columns = ['*'], $orderBy = 'id', $sort = 'asc')
    {
        $query = SubTask::select($columns)
            ->orderBy($orderBy, $sort)
            ->where('task_id', $taskId);

        if ($paginate) {
            return $query->paginate($perPage, $columns);
        }
        return $query->get();
    }

    public function getById($id)
    {
        return SubTask::find($id);
    }

    public function store(array $data)
    {
        return SubTask::create($data);
    }

    public function update($id, array $data)
    {
        $subTask = SubTask::find($id);

        if (!$subTask) {
            return false;
        }

        $subTask->update($data);

        return $subTask;
    }

    public function delete($id)
    {
        return SubTask::destroy($id);
    }
}
