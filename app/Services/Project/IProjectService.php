<?php

namespace App\Services\Project;

interface IProjectService
{
    public function all($paginate = false, $perPage = 15, $columns = ['*'], $orderBy = 'id', $sort = 'asc');
    public function getById($id);
    public function store($request);
    public function update($request, $id);
    public function delete($id);

}
