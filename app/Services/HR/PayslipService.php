<?php

namespace App\Services\HR;

use App\Models\HR\Payslip;
use App\Repositories\HR\PayslipRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\ValidationException;

class PayslipService
{
    public function __construct(
        protected PayslipRepository $repository
    ) {}

    public function getAll(): Collection
    {
        return $this->repository->all();
    }

    public function find(int $id): Payslip
    {
        return $this->repository->find($id);
    }

    public function approve(Payslip $payslip): Payslip
    {
        if ($payslip->status !== 'draft') {
            throw ValidationException::withMessages([
                'status' =>
                    'Only draft payslips can be approved.',
            ]);
        }

        return $this->repository->update(
            $payslip,
            [
                'status' => 'approved',
            ]
        );
    }

    public function markAsPaid(Payslip $payslip): Payslip
    {
        if ($payslip->status !== 'approved') {
            throw ValidationException::withMessages([
                'status' =>
                    'Only approved payslips can be paid.',
            ]);
        }

        return $this->repository->update(
            $payslip,
            [
                'status' => 'paid',
            ]
        );
    }
}
