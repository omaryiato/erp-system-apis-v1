<?php

namespace App\Repositories\Inventory;

use App\Models\Inventory\Project;

class ProjectRepository
{
    public function getAll()
    {
        return Project::all();
    }

    public function getDetails(Project $project): ?Project
    {
        return $project;
    }

    public function create(array $project_request): Project
    {
        return Project::create($project_request);
    }

    public function update(
        Project $project,
        array $project_request
    ): Project {
        $project->update($project_request);

        return $project->refresh();
    }

    public function delete(Project $project): bool
    {
        return (bool) $project->delete();
    }
}
