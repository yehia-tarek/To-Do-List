<?php

namespace App\Repositories\SubTask;

interface ISubTaskRepository
{
    public function all($taskId, $paginate = false, $perPage = 15, $columns = ['*'], $orderBy = 'id', $sort = 'asc');
    public function getById($id);
    public function store(array $data);
    public function update($id, array $data);
    public function delete($id);
}
