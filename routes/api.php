<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HR\EmployeeController;
use App\Http\Controllers\HR\AttendanceController;
use App\Http\Controllers\HR\PayrollItemTypeController;
use App\Http\Controllers\HR\EmployeePayrollItemController;

use App\Http\Controllers\HR\PayrollPeriodController;
use App\Http\Controllers\HR\PayslipController;
use App\Http\Controllers\HR\EmployeePayrollTransactionController;
use App\Http\Controllers\HR\PayrollProcessingController;

Route::prefix('payroll')->group(function () {

    Route::apiResource(
        'periods',
        PayrollPeriodController::class
    );

    Route::post(
        'periods/{payrollPeriod}/process',
        [PayrollProcessingController::class, 'process']
    );

    Route::get(
        'payslips',
        [PayslipController::class, 'index']
    );

    Route::get(
        'payslips/{payslip}',
        [PayslipController::class, 'show']
    );

    Route::post(
        'payslips/{payslip}/approve',
        [PayslipController::class, 'approve']
    );

    Route::post(
        'payslips/{payslip}/pay',
        [PayslipController::class, 'pay']
    );

    Route::apiResource(
        'transactions',
        EmployeePayrollTransactionController::class
    )->only([
        'index',
        'store',
        'show',
    ]);

    Route::post(
        'transactions/{transaction}/cancel',
        [EmployeePayrollTransactionController::class, 'cancel']
    );
});



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
