<?php

namespace App\Repositories\Tag;

use App\Models\Tag;
use App\Repositories\Tag\ITagRepository;

class TagRepository implements ITagRepository
{
    public function all($paginate = false, $perPage = 15, $columns = ['*'], $orderBy = 'id', $sort = 'asc')
    {
        $query = Tag::select($columns)->orderBy($orderBy, $sort);
        if ($paginate) {
            return $query->paginate($perPage, $columns);
        }
        return $query->get();
    }

    public function getById($id)
    {
        return Tag::find($id);
    }

    public function store(array $data)
    {
        return Tag::create($data);
    }

    public function update($id, array $data)
    {
        $tag = Tag::find($id);

        if (!$tag) {
            return null;
        }

        $tag->update($data);

        return $tag;
    }

    public function delete($id)
    {
        return Tag::where('id', $id)->delete();
    }
}
