<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\EmployeePaymentRequest;
use App\Http\Resources\EmployeePaymentResource;
use App\Models\Employee;
use App\Models\EmployeePayment;
use App\Services\EmployeePaymentService;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EmployeePaymentController extends Controller
{
    public function __construct(
        protected EmployeePaymentService $service
    ) {}

    public function index()
    {
        return ResponseHelper::success(
                EmployeePaymentResource::collection(
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
        EmployeePaymentRequest $request
    ) {

        try {
            $payment = $this->service->create(
                $request->validated()
            );


            return ResponseHelper::success(
                    new EmployeePaymentResource(
                        $payment
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

        return new EmployeePaymentResource($payment);
    }

    public function show(
        EmployeePayment $payment
    ) {

        return ResponseHelper::success(
                new EmployeePaymentResource(
                        $payment
                    ),
                [
                    'en' => trans('validation.get_employee_details', [], 'en'),
                    'ar' => trans('validation.get_employee_details', [], 'ar'),
                ],
                Response::HTTP_OK
            );
    }

    public function update(
        EmployeePaymentRequest $request,
        EmployeePayment $payment
    ) {

        try {
            $payment = $this->service->update(
                $payment,
                $request->validated()
            );

            return ResponseHelper::success(
                new EmployeePaymentResource(
                    $payment
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
        EmployeePayment $payment
    ) {
        return ResponseHelper::success(
                $this->service->delete($payment),
                [
                    'en' => trans('validation.delete_employee_payroll', [], 'en'),
                    'ar' => trans('validation.delete_employee_payroll', [], 'ar'),
                ],
                Response::HTTP_CREATED
            );
    }

    public function employeePayments(
        Request $request,
        Employee $employee
    ) {

        $payments = $this->service
            ->employeePayments(
                $employee,
                $request->from,
                $request->to
            );

        return ResponseHelper::success(
                EmployeePaymentResource::collection(
                        $payments
                    ),
                [
                    'en' => trans('validation.get_employee_attendance', [], 'en'),
                    'ar' => trans('validation.get_employee_attendance', [], 'ar'),
                ],
                Response::HTTP_OK
            );
    }
}
