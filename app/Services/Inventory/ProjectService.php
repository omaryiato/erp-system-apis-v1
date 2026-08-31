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

    public function getDetails(int $id): ?Project
    {
        return $this->repository->getDetails($id);
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
        return DB::transaction(
            fn () => $this->repository->delete($project)
        );
    }

    public function prepareProjectInfo(array $project_request)
    {
        $project_data =  [
            'name' => $project_request['name'] ?? null,
            'code' => $project_request['code'] ?? null,
            'phone' => $project_request['phone'] ?? null,
            'email' => $project_request['email'] ?? null,
            'address' => $project_request['address'] ?? null,
            'notes' => $project_request['notes'] ?? null,
            'status' => $project_request['status'] ?? null,
            'customer_name' => $project_request['customer_name'] ?? null,
        ];

        return $project_data;
    }
}
