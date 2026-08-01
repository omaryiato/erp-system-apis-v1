<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HR\EmployeeController;
use App\Http\Controllers\HR\AttendanceController;
use App\Http\Controllers\HR\PayrollItemTypeController;
use App\Http\Controllers\HR\EmployeePayrollItemController;





Route::apiResource(
    'employees',
    EmployeeController::class
);




Route::apiResource(
    'attendance',
    AttendanceController::class
);



Route::get(
    'employees/{employee}/attendance',
    [
        AttendanceController::class,
        'employeeAttendance'
    ]
);



Route::apiResource(
    'payroll-item-types',
    PayrollItemTypeController::class
);





Route::apiResource(
    'employee-payroll-items',
    EmployeePayrollItemController::class
);



Route::get(
'employees/{employee}/payroll-items',
[
EmployeePayrollItemController::class,
'employeeItems'
]
);



Route::get(
'employees/{employee}/active-payroll-items',
[
EmployeePayrollItemController::class,
'activeItems'
]
);
