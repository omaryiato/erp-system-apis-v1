<?php

namespace App\Repositories\Inventory;

use App\Models\Inventory\CashTransaction;
use App\Models\Inventory\Expense;
use App\Models\Inventory\Project;
use App\Models\Inventory\Purchase;
use App\Models\Inventory\Revenue;
use App\Models\Inventory\Supplier;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Carbon\Carbon;

class ReportRepository
{

    /*
    |--------------------------------------------------------------------------
    | Operation Report
    |--------------------------------------------------------------------------
    */

    public function operationReport(
        ?string $from = null,
        ?string $to = null ): array
    {
        $revenue = Revenue::query();
        $expense = Expense::query();
        $purchase = Purchase::query();

        if ($from) {
            $fromDate = Carbon::createFromFormat('m/Y', $from)->startOfMonth();

            $revenue->whereDate('revenue_date', '>=', $fromDate);
            $purchase->whereDate('purchase_date', '>=', $fromDate);
            $expense->whereDate('expense_date', '>=', $fromDate);
        }

        if ($to) {
            $toDate = Carbon::createFromFormat('m/Y', $to)->endOfMonth();

            $revenue->whereDate('revenue_date', '<=', $toDate);
            $purchase->whereDate('purchase_date', '<=', $toDate);
            $expense->whereDate('expense_date', '<=', $toDate);
        }

        $revenues = $revenue
            ->with([
                'project',
                'cashTransactions',
            ])
            ->get();

        $total_revenues = (float) $revenues->sum('amount');

        $received_revenues = (float) $revenues->sum(
            fn (Revenue $revenue) =>
                $revenue->cashTransactions->sum('amount')
        );

        $expenses = $expense
            ->with([
                'expensesCategory',
                'cashTransactions',
            ])
            ->get();

        $total_expenses = (float) $expenses->sum('amount');

        $paid_expenses = (float) $expenses->sum(
            fn (Expense $expense) =>
                $expense->cashTransactions->sum('amount')
        );

        $purchases = $purchase
            ->with([
                'supplier',
                'items.item',
                'items.allocations.project',
            ])
            ->get();

        $total_purchases = (float) $purchases->sum(
            fn (Purchase $purchase) =>
                $purchase->items->sum('total_amount')
        );

        return [
            'total_revenues' => $total_revenues,
            'received_revenues' => $received_revenues,
            'remaining_revenues' => max($total_revenues - $received_revenues, 0),

            'total_expenses' => $total_expenses,
            'paid_expenses' => $paid_expenses,
            'remaining_expenses' => max($total_expenses - $paid_expenses, 0),

            'total_purchases' => $total_purchases ,
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Financial Summary
    |--------------------------------------------------------------------------
    */

    public function financialSummary(array $filters): array
    {
        $expenseQuery = Expense::query();

        $revenueQuery = Revenue::query();

        $cashQuery = CashTransaction::query();

        $this->applyDateFilter(
            $expenseQuery,
            'expense_date',
            $filters
        );

        $this->applyDateFilter(
            $revenueQuery,
            'revenue_date',
            $filters
        );

        $this->applyDateFilter(
            $cashQuery,
            'transaction_date',
            $filters
        );

        $this->applyProjectFilter(
            $expenseQuery,
            $filters
        );

        $this->applyProjectFilter(
            $revenueQuery,
            $filters
        );

        $this->applyProjectFilter(
            $cashQuery,
            $filters
        );

        $totalExpenses = (float) $expenseQuery->sum('amount');

        $totalRevenues = (float) $revenueQuery->sum('amount');

        $totalPaid = (float) $cashQuery
            ->whereIn('transaction_type', [
                'expense',
                'supplier_payment',
                'employee_payment',
                'other_expense',
            ])
            ->sum('amount');

        $totalReceived = (float) $cashQuery
            ->whereIn('transaction_type', [
                'income',
                'other_income',
            ])
            ->sum('amount');

        return [
            'total_revenues' => $totalRevenues,
            'total_expenses' => $totalExpenses,
            'total_received' => $totalReceived,
            'total_paid' => $totalPaid,
            'net_profit' => $totalRevenues - $totalExpenses,
            'net_cash_flow' => $totalReceived - $totalPaid,
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Cash Flow
    |--------------------------------------------------------------------------
    */

    public function cashFlow(array $filters): array
    {
        $query = CashTransaction::query();

        $this->applyDateFilter(
            $query,
            'transaction_date',
            $filters
        );

        $this->applyProjectFilter(
            $query,
            $filters
        );

        $income = (float) $query
            ->clone()
            ->whereIn('transaction_type', [
                'income',
                'other_income',
            ])
            ->sum('amount');

        $expense = (float) $query
            ->clone()
            ->whereIn('transaction_type', [
                'expense',
                'supplier_payment',
                'employee_payment',
                'other_expense',
            ])
            ->sum('amount');

        return [
            'total_income' => $income,
            'total_expense' => $expense,
            'net_cash_flow' => $income - $expense,
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Project Financial
    |--------------------------------------------------------------------------
    */



    /*
    |--------------------------------------------------------------------------
    | Supplier Financial
    |--------------------------------------------------------------------------
    */



    /*
    |--------------------------------------------------------------------------
    | Outstanding Expenses
    |--------------------------------------------------------------------------
    */

    public function outstandingExpenses(
        array $filters
    ) {
        $query = Expense::query();

        $this->applyDateFilter(
            $query,
            'expense_date',
            $filters
        );

        $this->applyProjectFilter(
            $query,
            $filters
        );

        $this->applySupplierFilter(
            $query,
            $filters
        );

        return $query
            ->with([
                'project',
                'supplier',
                'cashTransactions',
            ])
            ->get()
            ->map(function (Expense $expense) {

                $paid = (float)
                    $expense->cashTransactions->sum('amount');

                $remaining =
                    (float) $expense->amount - $paid;

                return [
                    'expense' => $expense,
                    'amount' => (float) $expense->amount,
                    'paid_amount' => $paid,
                    'remaining_amount' =>
                        max($remaining, 0),
                ];
            })
            ->filter(
                fn (array $item) =>
                    $item['remaining_amount'] > 0
            )
            ->values();
    }

    /*
    |--------------------------------------------------------------------------
    | Outstanding Revenues
    |--------------------------------------------------------------------------
    */

    public function outstandingRevenues(
        array $filters
    ) {
        $query = Revenue::query();

        $this->applyDateFilter(
            $query,
            'revenue_date',
            $filters
        );

        $this->applyProjectFilter(
            $query,
            $filters
        );

        return $query
            ->with([
                'project',
                'cashTransactions',
            ])
            ->get()
            ->map(function (Revenue $revenue) {

                $received = (float)
                    $revenue->cashTransactions->sum('amount');

                $remaining =
                    (float) $revenue->amount - $received;

                return [
                    'revenue' => $revenue,
                    'amount' => (float) $revenue->amount,
                    'received_amount' => $received,
                    'remaining_amount' =>
                        max($remaining, 0),
                ];
            })
            ->filter(
                fn (array $item) =>
                    $item['remaining_amount'] > 0
            )
            ->values();
    }

    /*
    |--------------------------------------------------------------------------
    | Cash Transactions
    |--------------------------------------------------------------------------
    */

    public function cashTransactions(
        array $filters,
        int $perPage = 20
    ): LengthAwarePaginator {
        $query = CashTransaction::query()
            ->with([
                'project',
                'supplier',
                'expense',
                'revenue',
                'employeePayment',
                'purchaseOrder',
            ]);

        $this->applyDateFilter(
            $query,
            'transaction_date',
            $filters
        );

        $this->applyProjectFilter(
            $query,
            $filters
        );

        $this->applySupplierFilter(
            $query,
            $filters
        );

        if (!empty($filters['transaction_type'])) {
            $query->where(
                'transaction_type',
                $filters['transaction_type']
            );
        }

        return $query
            ->latest('transaction_date')
            ->latest('id')
            ->paginate($perPage);
    }

    /*
    |--------------------------------------------------------------------------
    | Monthly Financial
    |--------------------------------------------------------------------------
    */

    public function monthlyFinancial(
        array $filters
    ) {
        $from = $filters['from']
            ?? now()->startOfYear()->toDateString();

        $to = $filters['to']
            ?? now()->endOfYear()->toDateString();

        $expenses = Expense::query()
            ->when(
                $filters['project_id'] ?? null,
                fn ($query, $projectId) =>
                    $query->where(
                        'project_id',
                        $projectId
                    )
            )
            ->whereBetween(
                'expense_date',
                [$from, $to]
            )
            ->get([
                'amount',
                'expense_date',
            ]);

        $revenues = Revenue::query()
            ->when(
                $filters['project_id'] ?? null,
                fn ($query, $projectId) =>
                    $query->where(
                        'project_id',
                        $projectId
                    )
            )
            ->whereBetween(
                'revenue_date',
                [$from, $to]
            )
            ->get([
                'amount',
                'revenue_date',
            ]);

        $result = [];

        $start = \Carbon\Carbon::parse($from)
            ->startOfMonth();

        $end = \Carbon\Carbon::parse($to)
            ->startOfMonth();

        while ($start <= $end) {

            $month = $start->format('Y-m');

            $result[$month] = [
                'month' => $month,
                'revenue' => 0,
                'expense' => 0,
                'net' => 0,
            ];

            $start->addMonth();
        }

        foreach ($revenues as $revenue) {

            $month = $revenue->revenue_date
                ->format('Y-m');

            if (isset($result[$month])) {
                $result[$month]['revenue'] +=
                    (float) $revenue->amount;
            }
        }

        foreach ($expenses as $expense) {

            $month = $expense->expense_date
                ->format('Y-m');

            if (isset($result[$month])) {
                $result[$month]['expense'] +=
                    (float) $expense->amount;
            }
        }

        foreach ($result as &$row) {
            $row['net'] =
                $row['revenue'] -
                $row['expense'];
        }

        return array_values($result);
    }

    /*
    |--------------------------------------------------------------------------
    | Filters
    |--------------------------------------------------------------------------
    */

    private function applyDateFilter(
        $query,
        string $column,
        array $filters
    ): void {
        if (!empty($filters['from'])) {
            $query->whereDate(
                $column,
                '>=',
                $filters['from']
            );
        }

        if (!empty($filters['to'])) {
            $query->whereDate(
                $column,
                '<=',
                $filters['to']
            );
        }
    }

    private function applyProjectFilter(
        $query,
        array $filters
    ): void {
        if (!empty($filters['project_id'])) {
            $query->where(
                'project_id',
                $filters['project_id']
            );
        }
    }

    private function applySupplierFilter(
        $query,
        array $filters
    ): void {
        if (!empty($filters['supplier_id'])) {
            $query->where(
                'supplier_id',
                $filters['supplier_id']
            );
        }
    }


}
