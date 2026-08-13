<?php

namespace App\Services\HR;

use App\Models\HR\PayrollPeriod;
use App\Repositories\HR\PayrollPeriodRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\ValidationException;

class PayrollPeriodService
{
    public function __construct(
        protected PayrollPeriodRepository $repository
    ) {}

    public function getAll(): Collection
    {
        return $this->repository->all();
    }

    public function find(int $id): PayrollPeriod
    {
        return $this->repository->find($id);
    }

    public function create(array $data): PayrollPeriod
    {
        $exists = $this->repository->findByDates(
            $data['period_start'],
            $data['period_end']
        );

        if ($exists) {
            throw ValidationException::withMessages([
                'period_start' =>
                    'A payroll period already exists for these dates.',
            ]);
        }

        return $this->repository->create($data);
    }

    public function update(
        PayrollPeriod $period,
        array $data
    ): PayrollPeriod {
        if ($period->status !== 'draft') {
            throw ValidationException::withMessages([
                'status' =>
                    'Only draft payroll periods can be updated.',
            ]);
        }

        return $this->repository->update(
            $period,
            $data
        );
    }

    public function delete(PayrollPeriod $period): bool
    {
        if ($period->status !== 'draft') {
            throw ValidationException::withMessages([
                'status' =>
                    'Only draft payroll periods can be deleted.',
            ]);
        }

        return $this->repository->delete($period);
    }
}
