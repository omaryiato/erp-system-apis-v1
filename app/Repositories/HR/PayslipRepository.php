<?php

namespace App\Repositories\HR;

use App\Models\HR\Payslip;
use Illuminate\Database\Eloquent\Collection;

class PayslipRepository
{
    public function all(): Collection
    {
        return Payslip::query()
            ->with([
                'employee',
                'payrollPeriod',
                'items.payrollItemType',
            ])
            ->latest('generated_at')
            ->get();
    }

    public function find(int $id): Payslip
    {
        return Payslip::with([
            'employee',
            'payrollPeriod',
            'items.payrollItemType',
        ])->findOrFail($id);
    }

    public function findByPeriodAndEmployee(
        int $periodId,
        int $employeeId
    ): ?Payslip {
        return Payslip::where('payroll_period_id', $periodId)
            ->where('employee_id', $employeeId)
            ->first();
    }

    public function create(array $data): Payslip
    {
        return Payslip::create($data);
    }

    public function update(
        Payslip $payslip,
        array $data
    ): Payslip {
        $payslip->update($data);

        return $payslip->refresh();
    }

    public function delete(Payslip $payslip): bool
    {
        return $payslip->delete();
    }
}
