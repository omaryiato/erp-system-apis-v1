<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\HR\PayrollPeriod;
use App\Services\HR\PayrollProcessingService;
use Illuminate\Http\JsonResponse;

class PayrollProcessingController extends Controller
{
    public function __construct(
        protected PayrollProcessingService $service
    ) {}

    public function process(
        PayrollPeriod $payrollPeriod
    ): JsonResponse {
        $this->service->process($payrollPeriod);

        return response()->json([
            'message' =>
                'Payroll processed successfully.',
        ]);
    }
}
