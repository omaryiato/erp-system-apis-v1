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


use App\Http\Controllers\Attendance\EmployeeController;
use App\Http\Controllers\Attendance\AttendanceController;
use App\Http\Controllers\Attendance\EmployeeTransactionController;
use App\Http\Controllers\Attendance\EmployeePaymentController;
use App\Http\Controllers\Attendance\PayrollPeriodController;
use App\Http\Controllers\UserController;

use Illuminate\Support\Facades\DB;



use App\Http\Controllers\Inventory\CategoryController;
use App\Http\Controllers\Inventory\ItemController;
use App\Http\Controllers\Inventory\ProjectController;
use App\Http\Controllers\Inventory\SupplierController;
use App\Http\Controllers\Inventory\PurchaseController;



use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\Inventory\CashTransactionController;
use App\Http\Controllers\Inventory\ExpenseController;
use App\Http\Controllers\Inventory\RevenueController;
use App\Http\Controllers\Inventory\ReportsController;


Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::middleware([
    'auth:sanctum',
    'admin.access',
    'audit'
])->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/refresh', [AuthController::class, 'refresh']);

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
            'attendance/history',
            [AttendanceController::class, 'attendanceHistory']
        );

        Route::get(
            'attendance/history/{attendance}',
            [AttendanceController::class, 'attendanceHistoryDetails']
        );

        Route::get(
            'employees/{employee}/history/attendance',
            [AttendanceController::class, 'employeeHistoryAttendance']
        );

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

        Route::delete(
            'attendance/{attendance}',
            [AttendanceController::class, 'destroy']
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

        Route::delete(
            'payroll-periods/{period}',
            [PayrollPeriodController::class, 'destroy']
        );



        Route::get('truncate', function () {

            DB::statement('TRUNCATE TABLE
                payroll_periods_v1,
                employee_payments_v1,
                employee_transactions_v1,
                attendance_history_v1,
                attendance_v1,
                employees_v1,
                users_v1
                RESTART IDENTITY CASCADE
            ');

            return response()->json([
                'success' => true,
                'message' => 'Payroll tables truncated successfully.',
            ]);
        });

    });


    Route::prefix('inventory')->group(function () {

            /*
            |--------------------------------------------------------------------------
            | Categories
            |--------------------------------------------------------------------------
            */

            Route::apiResource(
                'categories',
                CategoryController::class
            );


            /*
            |--------------------------------------------------------------------------
            | Items
            |--------------------------------------------------------------------------
            */

            Route::apiResource(
                'items',
                ItemController::class
            );

            Route::get(
                'items/{id}/snapshots',
                [ItemController::class, 'snapshots']
            );


            /*
            |--------------------------------------------------------------------------
            | Projects
            |--------------------------------------------------------------------------
            */

            Route::apiResource(
                'projects',
                ProjectController::class
            );


            /*
            |--------------------------------------------------------------------------
            | Suppliers
            |--------------------------------------------------------------------------
            */

            Route::apiResource(
                'suppliers',
                SupplierController::class
            );

            /*
            |--------------------------------------------------------------------------
            | Purchases
            |--------------------------------------------------------------------------
            */


            Route::apiResource(
                'purchases',
                PurchaseController::class
            );

            /*
            |--------------------------------------------------------------------------
            | Expenses
            |--------------------------------------------------------------------------
            */

            Route::apiResource(
                'expenses',
                ExpenseController::class
            );

            /*
            |--------------------------------------------------------------------------
            | Revenues
            |--------------------------------------------------------------------------
            */

            Route::apiResource(
                'revenues',
                RevenueController::class
            );

            /*
            |--------------------------------------------------------------------------
            | Cash Transaction
            |--------------------------------------------------------------------------
            */

            Route::post(
                'cash-transactions',
                [CashTransactionController::class, 'store']
            );

            /*
            |--------------------------------------------------------------------------
            | Reports
            |--------------------------------------------------------------------------
            */

            Route::prefix('reports')
                ->controller(ReportsController::class)
                ->group(function () {

                    Route::get(
                        'financial-summary',
                        'financialSummary'
                    );

                    Route::get(
                        'cash-flow',
                        'cashFlow'
                    );

                    Route::get(
                        'expenses',
                        'expenses'
                    );

                    Route::get(
                        'revenues',
                        'revenues'
                    );

                    Route::get(
                        'projects/{projectId}/financial',
                        'projectFinancial'
                    );

                    Route::get(
                        'suppliers/{supplierId}/financial',
                        'supplierFinancial'
                    );

                    Route::get(
                        'outstanding-expenses',
                        'outstandingExpenses'
                    );

                    Route::get(
                        'outstanding-revenues',
                        'outstandingRevenues'
                    );

                    Route::get(
                        'cash-transactions',
                        'cashTransactions'
                    );

                    Route::get(
                        'monthly-financial',
                        'monthlyFinancial'
                    );
                });

            Route::get('truncate', function () {

                DB::statement('TRUNCATE TABLE
                    categories_v1,
                    items_v1,
                    item_snapshots_v1,
                    projects_v1,
                    suppliers_v1,
                    purchases_v1,
                    purchase_items_v1,
                    purchase_allocations_v1,
                    expenses_v1,
                    revenues_v1,
                    cash_transactions_v1
                    RESTART IDENTITY CASCADE
                ');

                return response()->json([
                    'success' => true,
                    'message' => 'Inventory tables truncated successfully.',
                ]);
            });
    });

    /***************************************** Users *******************************************/

    Route::apiResource('user', UserController::class);



});
