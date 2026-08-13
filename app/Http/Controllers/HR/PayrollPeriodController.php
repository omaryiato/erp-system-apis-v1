<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Http\Requests\HR\PayrollPeriod\AddNewPayrollPeriod;
use App\Http\Requests\Hr\PayrollPeriod\UpdatePayrollPeriod;
use App\Http\Requests\HR\PayrollPeriod\UpdatePayrollPeriodRequest;
use App\Http\Resources\PayrollPeriodResource;
use App\Models\HR\PayrollPeriod;
use App\Services\HR\PayrollPeriodService;
use Illuminate\Http\JsonResponse;

class PayrollPeriodController extends Controller
{
    public function __construct(
        protected PayrollPeriodService $service
    ) {}

    public function index()
    {
        return PayrollPeriodResource::collection(
            $this->service->getAll()
        );
    }

    public function store(
        AddNewPayrollPeriod $request
    ) {
        $period = $this->service->create(
            $request->validated()
        );

        return new PayrollPeriodResource($period);
    }

    public function show(PayrollPeriod $payrollPeriod)
    {
        return new PayrollPeriodResource(
            $this->service->find($payrollPeriod->id)
        );
    }

    public function update(
        UpdatePayrollPeriod $request,
        PayrollPeriod $payrollPeriod
    ) {
        $period = $this->service->update(
            $payrollPeriod,
            $request->validated()
        );

        return new PayrollPeriodResource($period);
    }

    public function destroy(
        PayrollPeriod $payrollPeriod
    ): JsonResponse {
        $this->service->delete($payrollPeriod);

        return response()->json([
            'message' =>
                'Payroll period deleted successfully.',
        ]);
    }
}
