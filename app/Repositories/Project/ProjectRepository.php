<?php

namespace App\Repositories\Project;

use App\Models\Project;
use App\Repositories\Project\IProjectRepository;

class ProjectRepository implements IProjectRepository
{
    public function all($paginate = false, $perPage = 15, $columns = ['*'], $orderBy = 'id', $sort = 'asc')
    {
        $query = Project::select($columns)->orderBy($orderBy, $sort);
        if ($paginate) {
            return $query->paginate($perPage, $columns);
        }
        return $query->get();
    }

    public function store($data)
    {
        return Project::create($data);
    }

    public function update($data, $id)
    {
        $project = $this->getById($id);
        
        if($project){
            $project->update($data);
        }

        return $project;
    }

    public function getById($id)
    {
        return Project::find($id);
    }

    public function delete($id)
    {
        return Project::where('id', $id)->delete();
    }

}
