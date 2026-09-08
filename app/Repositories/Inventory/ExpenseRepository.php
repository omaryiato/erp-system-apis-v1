<?php

namespace App\Repositories\Inventory;

use App\Models\Inventory\Expense;
use Carbon\Carbon;

class ExpenseRepository
{
    public function getAll() {
        return Expense::with([
                'expensesCategory',
                'cashTransactions',
            ])->get();
    }

    public function getDetails(Expense $expense): ?Expense
    {
        return $expense->load([
                'expensesCategory',
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

    /*
    |--------------------------------------------------------------------------
    | Expense Report
    |--------------------------------------------------------------------------
    */

    public function expensesReport(
        ?string $from = null,
        ?string $to = null ): array
    {
        $query = Expense::query();

        if ($from) {
            $fromDate = Carbon::createFromFormat('m/Y', $from)->startOfMonth();

            $query->whereDate('expense_date', '>=', $fromDate);
        }

        if ($to) {
            $toDate = Carbon::createFromFormat('m/Y', $to)->endOfMonth();

            $query->whereDate('expense_date', '<=', $toDate);
        }

        $expenses = $query
            ->with([
                'expensesCategory',
                'cashTransactions',
            ])
            ->get();

        $total = (float) $expenses->sum('amount');

        $paid = (float) $expenses->sum(
            fn (Expense $expense) =>
                $expense->cashTransactions->sum('amount')
        );

        return [
            'total' => $total,
            'paid' => $paid,
            'outstanding' => max($total - $paid, 0),
            'count' => $expenses->count(),
            'expenses' => $expenses,
        ];
    }
}
