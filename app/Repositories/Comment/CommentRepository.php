<?php

namespace App\Repositories\Comment;

use App\Models\Comment;
use App\Repositories\Comment\ICommentRepository;

class CommentRepository implements ICommentRepository
{
    public function allCommentsByTaskId($taskId, $paginate = false, $perPage = 15, $columns = ['*'], $orderBy = 'id', $sort = 'asc')
    {
        $query = Comment::select($columns)->where('task_id', $taskId)->orderBy($orderBy, $sort);
        if ($paginate) {
            return $query->paginate($perPage, $columns);
        }
        return $query->get();
    }

    public function getById($id)
    {
        return Comment::find($id);
    }

    public function store($data)
    {
        return Comment::create($data);
    }

    public function update($data, $id)
    {
        $comment = $this->getById($id);

        if (!$comment) {
            return false;
        }

        $comment->update($data);

        return $comment;
    }

    public function delete($id)
    {
        return Comment::where('id', $id)->delete();
    }
}
