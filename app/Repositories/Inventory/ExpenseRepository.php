<?php

namespace App\Repositories\Inventory;

use App\Models\Inventory\Expense;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ExpenseRepository
{
    public function getAll() {
        return Expense::query()
            ->with([
                'project',
                'supplier',
            ])
            ->latest('id');
    }

    public function getDetails(int $id): ?Expense
    {
        return Expense::query()
            ->with([
                'project',
                'supplier',
                'cashTransactions',
            ])
            ->find($id);
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
