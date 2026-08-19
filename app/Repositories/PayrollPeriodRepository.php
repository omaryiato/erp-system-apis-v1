<?php

namespace App\Repositories;

use App\Models\PayrollPeriod;

class PayrollPeriodRepository
{
    public function all()
    {
        return PayrollPeriod::latest('period_start')
            ->get();
    }

    public function find(int $id): PayrollPeriod
    {
        return PayrollPeriod::findOrFail($id);
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

    public function delete(
        PayrollPeriod $period
    ): void {
        $period->delete();
    }
}
