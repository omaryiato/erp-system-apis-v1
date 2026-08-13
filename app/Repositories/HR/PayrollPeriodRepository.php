<?php

namespace App\Repositories\HR;

use App\Models\HR\PayrollPeriod;
use Illuminate\Database\Eloquent\Collection;

class PayrollPeriodRepository
{
    public function all(): Collection
    {
        return PayrollPeriod::query()
            ->withCount('payslips')
            ->latest('period_start')
            ->get();
    }

    public function find(int $id): PayrollPeriod
    {
        return PayrollPeriod::with([
            'payslips.employee',
        ])->findOrFail($id);
    }

    public function create(array $data): PayrollPeriod
    {
        return PayrollPeriod::create($data);
    }

    public function update(
        PayrollPeriod $period,
        array $data
    ): PayrollPeriod {
        $period->update($data);

        return $period->refresh();
    }

    public function delete(PayrollPeriod $period): bool
    {
        return $period->delete();
    }

    public function findByDates(
        string $start,
        string $end
    ): ?PayrollPeriod {
        return PayrollPeriod::where('period_start', $start)
            ->where('period_end', $end)
            ->first();
    }
}
