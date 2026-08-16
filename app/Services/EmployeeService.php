<?php

namespace App\Services;

use App\Models\Employee;
use App\Repositories\EmployeeRepository;

class EmployeeService
{
    public function __construct(
        protected EmployeeRepository $repository
    ) {}

    public function all()
    {
        return $this->repository->all();
    }

    public function find(int $id): Employee
    {
        return $this->repository->find($id);
    }

    public function create(array $data): Employee
    {
        return $this->repository->create($data);
    }

    public function update(
        Employee $employee,
        array $data
    ): Employee {
        return $this->repository->update(
            $employee,
            $data
        );
    }

    public function delete(Employee $employee): void
    {
        $this->repository->delete($employee);
    }
}
