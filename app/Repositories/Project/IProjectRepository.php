<?php

namespace App\Repositories\Project;

interface IProjectRepository
{
    public function all($paginate = false, $perPage = 15, $columns = ['*'], $orderBy = 'id', $sort = 'asc');
    public function getById($id);
    public function store($data);
    public function update($data, $id);
    public function delete($id);
}
