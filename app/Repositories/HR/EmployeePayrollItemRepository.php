<?php

namespace App\Repositories\HR;

use App\Models\HR\Employee;
use App\Models\HR\EmployeePayrollItem;



class EmployeePayrollItemRepository{


    public function getAllEmployeePayrolls()
    {

        return EmployeePayrollItem::with([
            'employee',
            'payrollItemType'
        ])
        ->get();

    }




    public function getEmployeePayrollDetails(EmployeePayrollItem $employeePayroll)
    {

        return $employeePayroll->load([
            'employee',
            'payrollItemType'
        ]);

    }



    public function addNewEmployeePayroll(array $employee_payroll_request)
    {

        return EmployeePayrollItem::create($employee_payroll_request);

    }





    public function updateEmployeePayroll(EmployeePayrollItem $employeePayroll, array $employee_payroll_request)
    {
        $employeePayroll->update($employee_payroll_request);
        return $employeePayroll;
    }




    public function deleteEmployeePayroll(EmployeePayrollItem $employeePayroll)
    {
        return $employeePayroll->delete();
    }

    public function getEmployeePayrolls(
    Employee $employee,
    ?int $month = null,
    ?int $year = null
    ) {
        $month ??= now()->month;
        $year ??= now()->year;

        return EmployeePayrollItem::with('payrollItemType')
            ->where('employee_id', $employee->id)
            ->whereYear('effective_from', '<=', $year)
            ->whereMonth('effective_from', '<=', $month)
            ->get();
    }



    // public function getEmployeePayrolls(Employee $employee)
    // {

    //     return EmployeePayrollItem::with(
    //         'payrollItemType'
    //     )
    //     ->where('employee_id',$employee->id)
    //     ->get();

    // }





    // public function activeItems($employeeId,$date)
    // {


    // return EmployeePayrollItem::with(
    //     'payrollItemType'
    // )
    // ->where(
    //     'employee_id',
    //     $employeeId
    // )
    // ->whereDate(
    //     'effective_from',
    //     '<=',
    //     $date
    // )
    // ->where(function($q) use($date){

    //     $q->whereNull('effective_to')
    //     ->orWhereDate(
    //         'effective_to',
    //         '>=',
    //         $date
    //     );

    // })
    // ->get();


    // }



}
