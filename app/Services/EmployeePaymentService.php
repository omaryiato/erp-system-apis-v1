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
        return $this->repository->create($this->preparePaymentInfo($data));
    }

    public function update(
        EmployeePayment $payment,
        array $data
    ): EmployeePayment {
        return $this->repository->update(
            $payment,
            $this->preparePaymentInfo($data)
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

    public function preparePaymentInfo(array $attendance_request)
    {

        $attendance_data =  [
            'employee_id' => $attendance_request['employee_id'] ?? null,
            'payment_date' => $attendance_request['payment_date'] ?? now(),
            'amount' => $attendance_request['amount'] ?? null,
            'payment_type' => $attendance_request['payment_type'] ?? null,
            'period_start' => $attendance_request['period_start'] ?? 8,
            'period_end' => $attendance_request['period_end'] ?? 0,
            'notes' => $attendance_request['notes'] ?? 'active',
        ];

        return $attendance_data;
    }
}
