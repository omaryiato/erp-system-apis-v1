<?php

namespace App\Services\HR;

use App\Models\HR\Employee;
use App\Models\HR\EmployeePayrollItem;
use App\Repositories\HR\EmployeePayrollItemRepository;



class EmployeePayrollItemService
{


    public function __construct(
        protected EmployeePayrollItemRepository $repository
    )
    {}




    public function getAllEmployeePayrolls()
    {
        return $this->repository->getAllEmployeePayrolls();
    }




    public function getEmployeePayrollDetails(EmployeePayrollItem $employeePayroll)
    {
        return $this->repository->getEmployeePayrollDetails($employeePayroll);
    }





    public function addNewEmployeePayroll(array $employee_payroll_request)
    {
        return $this->repository->addNewEmployeePayroll(
            $this->prepareEmployeePayrollInfo($employee_payroll_request));
    }



    public function updateEmployeePayroll(EmployeePayrollItem $employeePayroll,array $employee_payroll_request)
    {
        return $this->repository->updateEmployeePayroll(
            $employeePayroll,
            $this->prepareEmployeePayrollInfo($employee_payroll_request)
        );
    }


    public function deleteEmployeePayroll(EmployeePayrollItem  $employeePayroll)
    {
        // $employee_payroll_details = $this->repository->getEmployeePayrollDetails($employee_payroll_request['id']);
        return $this->repository->deleteEmployeePayroll($employeePayroll);
    }

    public function getEmployeePayrolls(Employee $employee, int $month, int $year)
    {
        return $this->repository->getEmployeePayrolls($employee, $month, $year);
    }

    public function prepareEmployeePayrollInfo(array $employee_payroll_request)
    {
        return [
            'employee_id' => $employee_payroll_request['employee_id'] ?? null,
            'payroll_item_type_id' => $employee_payroll_request['payroll_item_type_id'] ?? null,
            'value' => $employee_payroll_request['value'] ?? 0,
            'effective_from' => now(),
        ];
    }





    // public function activeItems(
    //     $employeeId,
    //     $date
    // )
    // {

    // return $this->repository
    //             ->activeItems(
    //                 $employeeId,
    //                 $date
    //             );

    // }



}
