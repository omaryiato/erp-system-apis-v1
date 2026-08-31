<?php

namespace App\Repositories\Attendance;

use App\Models\Attendance\Employee;

class EmployeeRepository
{
    public function all()
    {
        return Employee::latest()->get();
    }

    public function find(int $id): Employee
    {
        return Employee::findOrFail($id);
    }

    public function create(array $data): Employee
    {
        return Employee::create($data);
    }

    public function update(
        Employee $employee,
        array $data
    ): Employee {
        $employee->update($data);

        return $employee->refresh();
    }

    public function delete(Employee $employee): void
    {
        $employee->delete();
    }
}
