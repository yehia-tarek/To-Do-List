<?php

namespace App\Services\Tag;

use App\Services\Tag\ITagService;
use App\Repositories\Tag\ITagRepository;

class TagService implements ITagService
{
    protected $tagRepository;

    public function __construct(ITagRepository $tagRepository)
    {
        $this->tagRepository = $tagRepository;
    }

    public function all($paginate = false, $perPage = 15, $columns = ['*'], $orderBy = 'id', $sort = 'asc')
    {
        return $this->tagRepository->all($paginate, $perPage, $columns, $orderBy, $sort);
    }

    public function getById($id)
    {
        return $this->tagRepository->getById($id);
    }

    public function store(array $data)
    {
        $data['user_id'] = auth()->user()->id;
        return $this->tagRepository->store($data);
    }

    public function update($id, array $data)
    {
        return $this->tagRepository->update($id, $data);
    }

    public function delete($id)
    {
        return $this->tagRepository->delete($id);
    }
}
