<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\PayrollPeriodRequest;
use App\Http\Resources\PayrollPeriodResource;
use App\Models\PayrollPeriod;
use App\Services\PayrollPeriodService;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PayrollPeriodController extends Controller
{
    public function __construct(
        protected PayrollPeriodService $service
    ) {}

    public function index()
    {
        return ResponseHelper::success(
                PayrollPeriodResource::collection(
                    $this->service->all()
                ),
                [
                    'en' => trans('validation.get_payroll_period', [], 'en'),
                    'ar' => trans('validation.get_payroll_period', [], 'ar'),
                ],
                Response::HTTP_CREATED
            );
    }

    public function store(
        PayrollPeriodRequest $request
    ) {

        try {
            $period = $this->service->create(
                    $request->validated()
                );

            return ResponseHelper::success(
                    new PayrollPeriodResource($period),
                    [
                        'en' => trans('validation.add_new_period', [], 'en'),
                        'ar' => trans('validation.add_new_period', [], 'ar'),
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

    public function show(PayrollPeriod $period)
    {
        return ResponseHelper::success(
                new PayrollPeriodResource(
                    $period
                ),
                [
                    'en' => trans('validation.get_period', [], 'en'),
                    'ar' => trans('validation.get_period', [], 'ar'),
                ],
                Response::HTTP_OK
            );
    }

    public function close(PayrollPeriod $period)
    {
        try {
            $period = $this->service->close($period);

            return ResponseHelper::success(
                new PayrollPeriodResource($period),
                [
                    'en' => trans('validation.update_employee', [], 'en'),
                    'ar' => trans('validation.update_employee', [], 'ar'),
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

    public function report(
        Request $request,
        PayrollPeriod $period
    ) {

        try {

            $report = $this->service->report(
                $period,
                $request->integer('employee_id')
                    ?: null
            );


            return ResponseHelper::success(
                [
                    'period' => new PayrollPeriodResource($period),
                    'data' => $report
                ],
                [
                    'en' => trans('validation.update_employee', [], 'en'),
                    'ar' => trans('validation.update_employee', [], 'ar'),
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
        PayrollPeriod $period
    ) {

        return ResponseHelper::success(
                $this->service->delete($period),
                [
                    'en' => trans('validation.delete_period', [], 'en'),
                    'ar' => trans('validation.delete_period', [], 'ar'),
                ],
                Response::HTTP_CREATED
            );
    }
}
