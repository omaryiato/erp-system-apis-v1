<?php

namespace App\Repositories;

use App\Models\EmployeePayment;
use App\Models\Employee;

class EmployeePaymentRepository
{
    public function getAll()
    {
        return EmployeePayment::all();
    }

    public function create(array $data): EmployeePayment
    {
        return EmployeePayment::create($data);
    }

    public function find(int $id): EmployeePayment
    {
        return EmployeePayment::findOrFail($id);
    }

    public function update(
        EmployeePayment $payment,
        array $data
    ): EmployeePayment {
        $payment->update($data);

        return $payment->refresh();
    }

    public function delete(EmployeePayment $payment): void
    {
        $payment->delete();
    }

    public function employeePayments(
        Employee $employee,
        ?string $from = null,
        ?string $to = null
    ) {
        return $employee->payments()
            ->when(
                $from,
                fn ($q) => $q->whereDate(
                    'payment_date',
                    '>=',
                    $from
                )
            )
            ->when(
                $to,
                fn ($q) => $q->whereDate(
                    'payment_date',
                    '<=',
                    $to
                )
            )
            ->latest('payment_date')
            ->get();
    }
}
