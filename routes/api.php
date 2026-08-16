<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\HR\EmployeeController;
// use App\Http\Controllers\HR\AttendanceController;
// use App\Http\Controllers\HR\PayrollItemTypeController;
// use App\Http\Controllers\HR\EmployeePayrollItemController;

// use App\Http\Controllers\HR\PayrollPeriodController;
// use App\Http\Controllers\HR\PayslipController;
// use App\Http\Controllers\HR\EmployeePayrollTransactionController;
// use App\Http\Controllers\HR\PayrollProcessingController;

// Route::prefix('payroll')->group(function () {

//     Route::apiResource(
//         'periods',
//         PayrollPeriodController::class
//     );

//     Route::post(
//         'periods/{payrollPeriod}/process',
//         [PayrollProcessingController::class, 'process']
//     );

//     Route::get(
//         'payslips',
//         [PayslipController::class, 'index']
//     );

//     Route::get(
//         'payslips/{payslip}',
//         [PayslipController::class, 'show']
//     );

//     Route::post(
//         'payslips/{payslip}/approve',
//         [PayslipController::class, 'approve']
//     );

//     Route::post(
//         'payslips/{payslip}/pay',
//         [PayslipController::class, 'pay']
//     );

//     Route::apiResource(
//         'transactions',
//         EmployeePayrollTransactionController::class
//     )->only([
//         'index',
//         'store',
//         'show',
//     ]);

//     Route::post(
//         'transactions/{transaction}/cancel',
//         [EmployeePayrollTransactionController::class, 'cancel']
//     );
// });



// Route::group(['prefix' => 'hr'], function () {

//     /*************************************** Employee APIs  ******************************************/

//         Route::apiResource(
//             'employees',
//             EmployeeController::class
//         );

//     /*************************************** Attendance APIs  ******************************************/

//         Route::apiResource(
//             'attendance',
//             AttendanceController::class
//         );



//         Route::get(
//             'employee/attendance/{employee}',
//             [
//                 AttendanceController::class,
//                 'employeeAttendance'
//             ]
//         );


//     /*************************************** Payroll Item Types APIs  ******************************************/


//         Route::apiResource(
//             'payroll-item-types',
//             PayrollItemTypeController::class
//         );

//     /*************************************** Employee Payroll Item APIs  ******************************************/


//         Route::apiResource(
//             'employee-payroll',
//             EmployeePayrollItemController::class
//         );


//         Route::get(
//         'employee/payroll/{employee}',
//         [
//         EmployeePayrollItemController::class,
//         'employeePayroll'
//         ]
//         );


//         // Route::get(
//         // 'employees/{employee}/active-payroll-items',
//         // [
//         // EmployeePayrollItemController::class,
//         // 'activeItems'
//         // ]
//         // );

// });


use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\EmployeeTransactionController;
use App\Http\Controllers\EmployeePaymentController;
use App\Http\Controllers\PayrollPeriodController;

Route::prefix('hr')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Employees
    |--------------------------------------------------------------------------
    */

    Route::apiResource(
        'employees',
        EmployeeController::class
    );


    /*
    |--------------------------------------------------------------------------
    | Attendance
    |--------------------------------------------------------------------------
    */

    Route::get(
        'attendance',
        [AttendanceController::class, 'index']
    );

    Route::post(
        'attendance',
        [AttendanceController::class, 'store']
    );


    Route::get(
        'attendance/{attendance}',
        [AttendanceController::class, 'show']
    );

    Route::put(
        'attendance/{attendance}',
        [AttendanceController::class, 'update']
    );

    Route::get(
        'employees/{employee}/attendance',
        [AttendanceController::class, 'employeeAttendance']
    );


    /*
    |--------------------------------------------------------------------------
    | Transactions
    |--------------------------------------------------------------------------
    */

    Route::get(
        'transactions',
        [EmployeeTransactionController::class, 'index']
    );

    Route::post(
        'transactions',
        [EmployeeTransactionController::class, 'store']
    );

    Route::get(
        'transactions/{transaction}',
        [EmployeeTransactionController::class, 'show']
    );

    Route::put(
        'transactions/{transaction}',
        [EmployeeTransactionController::class, 'update']
    );

    Route::delete(
        'transactions/{transaction}',
        [EmployeeTransactionController::class, 'destroy']
    );

    Route::get(
        'employees/{employee}/transactions',
        [
            EmployeeTransactionController::class,
            'employeeTransactions'
        ]
    );


    /*
    |--------------------------------------------------------------------------
    | Payments
    |--------------------------------------------------------------------------
    */

    Route::get(
        'payments',
        [EmployeePaymentController::class, 'index']
    );

    Route::post(
        'payments',
        [EmployeePaymentController::class, 'store']
    );

    Route::get(
        'payments/{payment}',
        [EmployeePaymentController::class, 'show']
    );

    Route::put(
        'payments/{payment}',
        [EmployeePaymentController::class, 'update']
    );

    Route::delete(
        'payments/{payment}',
        [EmployeePaymentController::class, 'destroy']
    );

    Route::get(
        'employees/{employee}/payments',
        [
            EmployeePaymentController::class,
            'employeePayments'
        ]
    );


    /*
    |--------------------------------------------------------------------------
    | Payroll Periods
    |--------------------------------------------------------------------------
    */

    Route::get(
        'payroll-periods',
        [PayrollPeriodController::class, 'index']
    );

    Route::post(
        'payroll-periods',
        [PayrollPeriodController::class, 'store']
    );

    Route::get(
        'payroll-periods/{period}',
        [PayrollPeriodController::class, 'show']
    );

    Route::post(
        'payroll-periods/{period}/close',
        [PayrollPeriodController::class, 'close']
    );

    Route::get(
        'payroll-periods/{period}/report',
        [PayrollPeriodController::class, 'report']
    );
});
