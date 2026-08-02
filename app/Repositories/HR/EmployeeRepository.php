<?php

namespace App\Repositories\HR;


use App\Models\HR\Employee;


class EmployeeRepository
{


    public function getAllEmployee()
    {
        return Employee::with([
            'department',
            'position'
        ])->get();
    }



    public function getEmployeeDetails(Employee $employee)
    {
        return $employee->load([
            'department',
            'position'
        ]);
    }


    public function addNewEmployee(array $employee_request)
    {
        return Employee::create($employee_request);
    }


    public function updateEmployeeInfo(Employee $employee,array $employee_request)
    {
        $employee->update($employee_request);
        return $employee;
    }



    public function deleteEmployee(Employee $employee)
    {
        $employee->delete();
        return $employee;
    }


}
