<?php

namespace App\Services\Attendance;

use App\Models\Attendance\Employee;
use App\Repositories\Attendance\EmployeeRepository;

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
        return $this->repository->create($this->prepareEmployeeInfo($data));
    }

    public function update(
        Employee $employee,
        array $data
    ): Employee {
        return $this->repository->update(
            $employee,
            $this->prepareEmployeeInfo($data)
        );
    }

    public function delete(Employee $employee): Employee
    {
        $data = [
            'termination_date' => now(),
            'status' => 'inactive',
        ];

        return $this->repository->delete($employee, $data);
    }

    public function prepareEmployeeInfo(array $employee_request)
    {
        return [
            'employee_number' => $employee_request['employee_number'] ?? null,
            'full_name' => $employee_request['full_name'] ?? null,
            'phone' => $employee_request['phone'],
            'hire_date' => $employee_request['hire_date'] ?? today(),
            'position_id' => $employee_request['position_id'] ?? null,
            'termination_date' => $employee_request['termination_date'] ?? null,
            'salary_type' => $employee_request['salary_type'] ?? 'active',
            'base_salary' => $employee_request['base_salary'] ?? 0,
            'overtime_rate' => $employee_request['overtime_rate'] ?? null,
            'daily_worked_hours' => $employee_request['daily_worked_hours'] ?? 9,
            'status' => $employee_request['status'] ?? 'daily',
        ];
    }
}
