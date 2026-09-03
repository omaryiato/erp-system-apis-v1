<?php

namespace App\Repositories\Inventory;

use App\Models\Inventory\Expense;

class ExpenseRepository
{
    public function getAll() {
        return Expense::with([
                'project',
                'supplier',
            ])
            ->get();
    }

    public function getDetails(Expense $expense): ?Expense
    {
        return $expense->load([
                'project',
                'supplier',
                'cashTransactions',
            ]);
    }

    public function create(array $expense_request): Expense
    {
        return Expense::create($expense_request);
    }

    public function update(
        Expense $expense,
        array $expense_request
    ): Expense {
        $expense->update($expense_request);

        return $expense->refresh();
    }

    public function delete(
        Expense $expense
    ): bool {
        return (bool) $expense->delete();
    }
}
