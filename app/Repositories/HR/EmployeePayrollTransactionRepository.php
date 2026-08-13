<?php

namespace App\Repositories\HR;

use App\Models\HR\EmployeePayrollTransaction;
use Illuminate\Database\Eloquent\Collection;

class EmployeePayrollTransactionRepository
{
    public function all(): Collection
    {
        return EmployeePayrollTransaction::query()
            ->with('employee')
            ->latest('transaction_date')
            ->get();
    }

    public function find(int $id): EmployeePayrollTransaction
    {
        return EmployeePayrollTransaction::with(
            'employee'
        )->findOrFail($id);
    }

    public function getEmployeeTransactions(
        int $employeeId
    ): Collection {
        return EmployeePayrollTransaction::where(
            'employee_id',
            $employeeId
        )
        ->latest('transaction_date')
        ->get();
    }

    public function getActiveAdvances(
        int $employeeId
    ): Collection {
        return EmployeePayrollTransaction::where(
            'employee_id',
            $employeeId
        )
        ->where('type', 'advance')
        ->where('status', 'active')
        ->where('remaining_amount', '>', 0)
        ->get();
    }

    public function create(
        array $data
    ): EmployeePayrollTransaction {
        return EmployeePayrollTransaction::create($data);
    }

    public function update(
        EmployeePayrollTransaction $transaction,
        array $data
    ): EmployeePayrollTransaction {
        $transaction->update($data);

        return $transaction->refresh();
    }

    public function delete(
        EmployeePayrollTransaction $transaction
    ): bool {
        return $transaction->delete();
    }
}
