<?php

namespace App\Http\Controllers\Attendance;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Attendance\EmployeeTransactionRequest;
use App\Http\Resources\Attendance\EmployeeTransactionResource;
use App\Models\Attendance\Employee;
use App\Models\Attendance\EmployeeTransaction;
use App\Services\Attendance\EmployeeTransactionService;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EmployeeTransactionController extends Controller
{
    public function __construct(
        protected EmployeeTransactionService $service
    ) {}

    public function index()
    {
        return ResponseHelper::success(
                EmployeeTransactionResource::collection(
                    $this->service->getAll()
                ),
                [
                    'en' => trans('validation.get_employee_transaction_list', [], 'en'),
                    'ar' => trans('validation.get_employee_transaction_list', [], 'ar'),
                ],
                Response::HTTP_OK
            );
    }

    public function store(
        EmployeeTransactionRequest $request
    ) {

        try {
            $transaction = $this->service->create(
                $request->validated()
            );


            return ResponseHelper::success(
                    new EmployeeTransactionResource(
                        $transaction
                    ),
                    [
                        'en' => trans('validation.add_new_employee', [], 'en'),
                        'ar' => trans('validation.add_new_employee', [], 'ar'),
                    ],
                    Response::HTTP_CREATED
                );
        } catch (Exception $exception) {
            return ResponseHelper::error(
                [
                    'en' => trans('validation.exception_error', [], 'en'),
                    'ar' => trans('validation.exception_error', [], 'ar'),
                ],
                $exception->getMessage(),
                500);
        }
    }

    public function show(
        EmployeeTransaction $transaction
    ) {
        return ResponseHelper::success(
                new EmployeeTransactionResource(
                        $transaction
                    ),
                [
                    'en' => trans('validation.get_employee_details', [], 'en'),
                    'ar' => trans('validation.get_employee_details', [], 'ar'),
                ],
                Response::HTTP_OK
            );
    }

    public function update(
        EmployeeTransactionRequest $request,
        EmployeeTransaction $transaction
    ) {

        try {
            $transaction = $this->service->update(
                $transaction,
                $request->validated()
            );

            return ResponseHelper::success(
                new EmployeeTransactionResource(
                    $transaction
                ),
                [
                    'en' => trans('validation.update_employee_payroll', [], 'en'),
                    'ar' => trans('validation.update_employee_payroll', [], 'ar'),
                ],
                Response::HTTP_CREATED
            );
        } catch (Exception $exception) {
            return ResponseHelper::error(
                [
                    'en' => trans('validation.exception_error', [], 'en'),
                    'ar' => trans('validation.exception_error', [], 'ar'),
                ],
                $exception->getMessage(),
                500);
        }
    }

    public function destroy(
        EmployeeTransaction $transaction
    ) {

        return ResponseHelper::success(
                $this->service->delete($transaction),
                [
                    'en' => trans('validation.delete_employee_payroll', [], 'en'),
                    'ar' => trans('validation.delete_employee_payroll', [], 'ar'),
                ],
                Response::HTTP_CREATED
            );
    }

    public function employeeTransactions(
        Request $request,
        Employee $employee
    ) {

        $transactions = $this->service
            ->employeeTransactions(
                $employee,
                $request->from,
                $request->to
            );

        return ResponseHelper::success(
                EmployeeTransactionResource::collection(
                        $transactions
                    ),
                [
                    'en' => trans('validation.get_employee_attendance', [], 'en'),
                    'ar' => trans('validation.get_employee_attendance', [], 'ar'),
                ],
                Response::HTTP_OK
            );
    }
}
