<?php

namespace App\Repositories\Attendance;

use App\Models\Attendance\EmployeePayment;
use App\Models\Attendance\Employee;
use Carbon\Carbon;

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

    // public function employeePayments(
    //     Employee $employee,
    //     ?string $from = null,
    //     ?string $to = null
    // ) {
    //     return $employee->payments()
    //         ->when(
    //             $from,
    //             fn ($q) => $q->whereDate(
    //                 'payment_date',
    //                 '>=',
    //                 $from
    //             )
    //         )
    //         ->when(
    //             $to,
    //             fn ($q) => $q->whereDate(
    //                 'payment_date',
    //                 '<=',
    //                 $to
    //             )
    //         )
    //         ->latest('payment_date')
    //         ->get();
    // }

    public function employeePayments(
        Employee $employee,
        ?string $from = null,
        ?string $to = null
    ) {

        $query = $employee->payments();

        if ($from) {
            $fromDate = Carbon::createFromFormat('m/Y', $from)->startOfMonth();

            $query->whereDate('payment_date', '>=', $fromDate);
        }

        if ($to) {
            $toDate = Carbon::createFromFormat('m/Y', $to)->endOfMonth();

            $query->whereDate('payment_date', '<=', $toDate);
        }

        return $query
            ->latest('payment_date')
            ->get();

    }
}
