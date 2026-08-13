<?php

namespace App\Services\HR;

use App\Models\HR\PayrollPeriod;
use App\Models\HR\Payslip;
use App\Models\HR\PayslipItem;
use App\Models\HR\Employee;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PayrollProcessingService
{
    public function process(PayrollPeriod $period): void
    {
        if ($period->status !== 'draft') {
            throw ValidationException::withMessages([
                'status' =>
                    'Only draft payroll periods can be processed.',
            ]);
        }

        DB::transaction(function () use ($period) {

            $employees = Employee::query()
                ->where('status', 'active')
                ->get();

            foreach ($employees as $employee) {

                $payslip = Payslip::create([
                    'payroll_period_id' => $period->id,
                    'employee_id' => $employee->id,
                    'base_salary' => $employee->base_salary,
                    'total_allowances' => 0,
                    'total_deductions' => 0,
                    'net_salary' => $employee->base_salary,
                    'status' => 'draft',
                    'generated_at' => now(),
                ]);

                // هنا لاحقًا:
                // 1. Calculate attendance
                // 2. Get employee transactions
                // 3. Apply deductions
                // 4. Create payslip_items
                // 5. Calculate totals

            }

            $period->update([
                'status' => 'processed',
            ]);
        });
    }
}
