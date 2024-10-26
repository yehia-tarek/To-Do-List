<?php

namespace App\Services\Comment;

use App\Repositories\Comment\ICommentRepository;

class CommentService implements ICommentService
{
    protected $commentRepository;

    public function __construct(ICommentRepository $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }

    public function allCommentsByTaskId($taskId, $paginate = false, $perPage = 15, $columns = ['*'], $orderBy = 'id', $sort = 'asc')
    {
        return $this->commentRepository->allCommentsByTaskId($taskId, $paginate, $perPage, $columns, $orderBy, $sort);
    }

    public function getById($id)
    {
        return $this->commentRepository->getById($id);
    }

    public function store(array $data)
    {
        $data['user_id'] = auth()->user()->id;

        return $this->commentRepository->store($data);
    }

    public function update($id, array $data)
    {
        return $this->commentRepository->update($data, $id);
    }

    public function delete($id)
    {
        return $this->commentRepository->delete($id);
    }
}
