<?php

namespace App\Http\Controllers\HR;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\PayslipResource;
use App\Models\HR\Payslip;
use App\Services\HR\PayslipService;
use Exception;
use Symfony\Component\HttpFoundation\Response;

class PayslipController extends Controller
{
    public function __construct(
        protected PayslipService $service
    ) {}

    public function index()
    {
        return ResponseHelper::success(
                PayslipResource::collection(
                    $this->service->getAll()
                ),
                [
                    'en' => trans('validation.get_employee_transaction_list', [], 'en'),
                    'ar' => trans('validation.get_employee_transaction_list', [], 'ar'),
                ],
                Response::HTTP_OK
            );
    }

    public function show(Payslip $payslip)
    {
        return ResponseHelper::success(
                new PayslipResource(
                    $this->service->find($payslip->id)
                ),
                [
                    'en' => trans('validation.get_employee_details', [], 'en'),
                    'ar' => trans('validation.get_employee_details', [], 'ar'),
                ],
                Response::HTTP_OK
            );
    }

    public function approve(Payslip $payslip)
    {
        try {

            return ResponseHelper::success(
                    new PayslipResource(
                        $this->service->approve($payslip)
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

    public function pay(Payslip $payslip)
    {
        try {

            return ResponseHelper::success(
                    new PayslipResource(
                        $this->service->markAsPaid($payslip)
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
}
