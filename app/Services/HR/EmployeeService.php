<?php

namespace App\Services\HR;

use App\Models\HR\Employee;
use App\Repositories\HR\EmployeeRepository;



class EmployeeService
{


    public function __construct(
        protected EmployeeRepository $repository
    )
    {}



    public function getAllEmployee()
    {
        return $this->repository->getAllEmployee();
    }



    public function getEmployeeDetails(Employee $employee)
    {
        return $this->repository->getEmployeeDetails($employee);
    }

    public function addNewEmployee(array $employee_request)
    {
        return $this->repository->addNewEmployee($this->prepareEmployeeInfo($employee_request));
    }

    public function updateEmployeeInfo(Employee $employee,array $employee_request)
    {
        return $this->repository->updateEmployeeInfo(
            $employee,
            $this->prepareEmployeeInfo($employee_request)
        );
    }


    public function deleteEmployee(array $employee_request)
    {
        $employee = $this->repository->getEmployeeDetails($employee_request['id']);
        return $this->repository->deleteEmployee($employee);
    }

    public function prepareEmployeeInfo(array $employee_request)
    {
        return [
            'employee_id_number' => $employee_request['employee_id_number'] ?? null,
            'full_name' => $employee_request['full_name'] ?? null,
            'national_id' => $employee_request['national_id'],
            'department_id' => $employee_request['department_id'] ?? null,
            'position_id' => $employee_request['position_id'] ?? null,
            'hire_date' => $employee_request['hire_date'] ?? null,
            'termination_date' => $employee_request['termination_date'] ?? null,
            'status' => $employee_request['status'] ?? 'active',
            'base_salary' => $employee_request['base_salary'] ?? 0,
            'shift_id' => $employee_request['shift_id'] ?? null,
            'biometric_code' => $employee_request['biometric_code'] ?? null,
        ];
    }



}
