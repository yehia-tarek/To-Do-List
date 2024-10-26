<?php

namespace App\Services\Tag;

interface ITagService
{
    public function all($paginate = false, $perPage = 15, $columns = ['*'], $orderBy = 'id', $sort = 'asc');
    public function getById($id);
    public function store(array $data);
    public function update($id, array $data);
    public function delete($id);
}
