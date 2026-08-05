<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HR\EmployeeController;
use App\Http\Controllers\HR\AttendanceController;
use App\Http\Controllers\HR\PayrollItemTypeController;
use App\Http\Controllers\HR\EmployeePayrollItemController;



Route::group(['prefix' => 'hr'], function () {

    /*************************************** Employee APIs  ******************************************/

        Route::apiResource(
            'employees',
            EmployeeController::class
        );

    /*************************************** Attendance APIs  ******************************************/

        Route::apiResource(
            'attendance',
            AttendanceController::class
        );



        Route::get(
            'employee/attendance/{employee}',
            [
                AttendanceController::class,
                'employeeAttendance'
            ]
        );


    /*************************************** Payroll Item Types APIs  ******************************************/


        Route::apiResource(
            'payroll-item-types',
            PayrollItemTypeController::class
        );

    /*************************************** Employee Payroll Item APIs  ******************************************/


        Route::apiResource(
            'employee-payroll',
            EmployeePayrollItemController::class
        );


        Route::get(
        'employee/payroll/{employee}',
        [
        EmployeePayrollItemController::class,
        'employeePayroll'
        ]
        );


        // Route::get(
        // 'employees/{employee}/active-payroll-items',
        // [
        // EmployeePayrollItemController::class,
        // 'activeItems'
        // ]
        // );

});
