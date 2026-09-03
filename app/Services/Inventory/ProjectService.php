<?php

namespace App\Services\Inventory;

use App\Models\Inventory\Project;
use App\Repositories\Inventory\ProjectRepository;
use Illuminate\Support\Facades\DB;

class ProjectService
{
    public function __construct(
        private ProjectRepository $repository
    ) {}

    public function getAll()
    {
        return $this->repository->getAll();
    }

    public function getDetails(Project $project): ?Project
    {
        return $this->repository->getDetails($project);
    }

    public function create(array $project_request): Project
    {
        return $this->repository->create($this->prepareProjectInfo($project_request));
    }

    public function update(
        Project $project,
        array $project_request
    ): Project {
        return $this->repository->update(
            $project,
            $this->prepareProjectInfo($project_request)
        );
    }

    public function delete(Project $project): bool
    {
        return   $this->repository->delete($project);
    }

    public function prepareProjectInfo(array $project_request)
    {

        $project_data =  [
            'project_code' => $project_request['project_code'] ?? null,
            'project_name' => $project_request['project_name'] ?? null,
            'customer_name' => $project_request['customer_name'] ?? null,
            'phone' => $project_request['phone'] ?? null,
            'email' => $project_request['email'] ?? null,
            'address' => $project_request['address'] ?? null,
            'description' => $project_request['description'] ?? null,
            'start_date' => $project_request['start_date'] ?? null,
            'end_date' => $project_request['end_date'] ?? null,
            'status' => $project_request['status'] ?? null,
        ];

        return $project_data;
    }
}
