<?php

namespace App\Repositories\Comment;

interface ICommentRepository
{
    public function allCommentsByTaskId($taskId ,$paginate = false, $perPage = 15, $columns = ['*'], $orderBy = 'id', $sort = 'asc');
    public function getById($id);
    public function store($data);
    public function update($data, $id);
    public function delete($id);
}
