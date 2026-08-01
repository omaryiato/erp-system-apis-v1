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



    public function getEmployeeDetails($id)
    {

        return Employee::with([
            'department',
            'position'
        ])
        ->findOrFail($id);

    }



    public function addNewEmployee(array $data)
    {
        return Employee::create($data);
    }



    public function updateEmployeeInfo($id,array $data)
    {

        $employee = Employee::findOrFail($id);

        $employee->update($data);

        return $employee;

    }



    public function deleteEmployee($id)
    {

        return Employee::findOrFail($id)->delete();

    }


}
