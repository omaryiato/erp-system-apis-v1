<?php

namespace App\Http\Controllers\HR;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\HR\EmployeePayrollTransaction\AddNewEmployeePayrollTransaction;
use App\Http\Resources\EmployeePayrollTransactionResource;
use App\Models\HR\EmployeePayrollTransaction;
use App\Services\HR\EmployeePayrollTransactionService;
use Exception;
use Symfony\Component\HttpFoundation\Response;

class EmployeePayrollTransactionController extends Controller
{
    public function __construct(
        protected EmployeePayrollTransactionService $service
    ) {}

    public function index()
    {
        return ResponseHelper::success(
                EmployeePayrollTransactionResource::collection(
                    $this->service->getAll()
                ),
                [
                    'en' => trans('validation.get_employee_transaction_list', [], 'en'),
                    'ar' => trans('validation.get_employee_transaction_list', [], 'ar'),
                ],
                Response::HTTP_OK
            );
    }

    public function store(AddNewEmployeePayrollTransaction $request)
    {

        try {
            $transaction = $this->service->create(
                    $request->validated()
                );

            return ResponseHelper::success(
                    new EmployeePayrollTransactionResource(
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

    public function show(EmployeePayrollTransaction $transaction)
    {
        return ResponseHelper::success(
                new EmployeePayrollTransactionResource(
                        $this->service->find($transaction->id)
                    ),
                [
                    'en' => trans('validation.get_employee_details', [], 'en'),
                    'ar' => trans('validation.get_employee_details', [], 'ar'),
                ],
                Response::HTTP_OK
            );
    }

    public function cancel(EmployeePayrollTransaction $transaction) {
        $transaction = $this->service->cancel(
            $transaction
        );

        return ResponseHelper::success(
                new EmployeePayrollTransactionResource(
                    $transaction
                ),
                [
                    'en' => trans('validation.delete_employee', [], 'en'),
                    'ar' => trans('validation.delete_employee', [], 'ar'),
                ],
                Response::HTTP_CREATED
            );
    }
}
