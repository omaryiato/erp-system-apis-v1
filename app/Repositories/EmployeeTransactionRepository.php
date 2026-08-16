<?php

namespace App\Repositories;

use App\Models\EmployeeTransaction;
use App\Models\Employee;

class EmployeeTransactionRepository
{
    public function getAll()
    {
        return EmployeeTransaction::all();
    }

    public function create(array $data): EmployeeTransaction
    {
        return EmployeeTransaction::create($data);
    }

    public function find(int $id): EmployeeTransaction
    {
        return EmployeeTransaction::findOrFail($id);
    }

    public function update(
        EmployeeTransaction $transaction,
        array $data
    ): EmployeeTransaction {
        $transaction->update($data);

        return $transaction->refresh();
    }

    public function delete(
        EmployeeTransaction $transaction
    ): void {
        $transaction->delete();
    }

    public function employeeTransactions(
        Employee $employee,
        ?string $from = null,
        ?string $to = null
    ) {
        return $employee->transactions()
            ->when(
                $from,
                fn ($q) => $q->whereDate(
                    'transaction_date',
                    '>=',
                    $from
                )
            )
            ->when(
                $to,
                fn ($q) => $q->whereDate(
                    'transaction_date',
                    '<=',
                    $to
                )
            )
            ->latest('transaction_date')
            ->get();
    }
}
