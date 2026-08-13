<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Http\Resources\PayslipResource;
use App\Models\HR\Payslip;
use App\Services\HR\PayslipService;

class PayslipController extends Controller
{
    public function __construct(
        protected PayslipService $service
    ) {}

    public function index()
    {
        return PayslipResource::collection(
            $this->service->getAll()
        );
    }

    public function show(Payslip $payslip)
    {
        return new PayslipResource(
            $this->service->find($payslip->id)
        );
    }

    public function approve(Payslip $payslip)
    {
        return new PayslipResource(
            $this->service->approve($payslip)
        );
    }

    public function pay(Payslip $payslip)
    {
        return new PayslipResource(
            $this->service->markAsPaid($payslip)
        );
    }
}
