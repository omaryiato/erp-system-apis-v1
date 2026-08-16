<?php

namespace App\Services;

use App\Models\EmployeePayment;
use App\Repositories\EmployeePaymentRepository;

class EmployeePaymentService
{
    public function __construct(
        protected EmployeePaymentRepository $repository
    ) {}

    public function getAll()
    {
        return $this->repository->getAll();
    }

    public function create(array $data): EmployeePayment
    {
        return $this->repository->create($data);
    }

    public function update(
        EmployeePayment $payment,
        array $data
    ): EmployeePayment {
        return $this->repository->update(
            $payment,
            $data
        );
    }

    public function delete(
        EmployeePayment $payment
    ): void {
        $this->repository->delete($payment);
    }

    public function employeePayments(
        $employee,
        ?string $from = null,
        ?string $to = null
    ) {
        return $this->repository
            ->employeePayments(
                $employee,
                $from,
                $to
            );
    }
}
