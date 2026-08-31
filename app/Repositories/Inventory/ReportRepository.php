<?php

namespace App\Repositories\Inventory;

use App\Models\Inventory\CashTransaction;
use App\Models\Inventory\Expense;
use App\Models\Inventory\Project;
use App\Models\Inventory\Revenue;
use App\Models\Inventory\Supplier;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ReportRepository
{
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
    | Expense Report
    |--------------------------------------------------------------------------
    */

    public function expenseReport(array $filters): array
    {
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

        $this->applyCategoryFilter(
            $query,
            $filters
        );

        $expenses = $query
            ->with([
                'project',
                'supplier',
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

    /*
    |--------------------------------------------------------------------------
    | Revenue Report
    |--------------------------------------------------------------------------
    */

    public function revenueReport(array $filters): array
    {
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

        $this->applyCategoryFilter(
            $query,
            $filters
        );

        $revenues = $query
            ->with([
                'project',
                'cashTransactions',
            ])
            ->get();

        $total = (float) $revenues->sum('amount');

        $received = (float) $revenues->sum(
            fn (Revenue $revenue) =>
                $revenue->cashTransactions->sum('amount')
        );

        return [
            'total' => $total,
            'received' => $received,
            'outstanding' => max($total - $received, 0),
            'count' => $revenues->count(),
            'revenues' => $revenues,
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Project Financial
    |--------------------------------------------------------------------------
    */

    public function projectFinancial(
        int $projectId,
        array $filters = []
    ): array {
        $project = Project::query()
            ->findOrFail($projectId);

        $expenseQuery = $project
            ->expenses();

        $revenueQuery = $project
            ->revenues();

        $cashQuery = $project
            ->cashTransactions();

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

        $totalExpenses = (float)
            $expenseQuery->sum('amount');

        $totalRevenues = (float)
            $revenueQuery->sum('amount');

        $paid = (float) $cashQuery
            ->clone()
            ->whereIn('transaction_type', [
                'expense',
                'supplier_payment',
                'employee_payment',
                'other_expense',
            ])
            ->sum('amount');

        $received = (float) $cashQuery
            ->clone()
            ->whereIn('transaction_type', [
                'income',
                'other_income',
            ])
            ->sum('amount');

        return [
            'project' => $project,

            'total_revenue' =>
                $totalRevenues,

            'total_expenses' =>
                $totalExpenses,

            'received' =>
                $received,

            'paid' =>
                $paid,

            'profit' =>
                $totalRevenues - $totalExpenses,

            'receivable' =>
                max($totalRevenues - $received, 0),

            'payable' =>
                max($totalExpenses - $paid, 0),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Supplier Financial
    |--------------------------------------------------------------------------
    */

    public function supplierFinancial(
        int $supplierId,
        array $filters = []
    ): array {
        $supplier = Supplier::query()
            ->findOrFail($supplierId);

        $expenseQuery = $supplier->expenses();

        $cashQuery = $supplier->cashTransactions();

        $this->applyDateFilter(
            $expenseQuery,
            'expense_date',
            $filters
        );

        $this->applyDateFilter(
            $cashQuery,
            'transaction_date',
            $filters
        );

        $totalExpenses = (float)
            $expenseQuery->sum('amount');

        $totalPaid = (float) $cashQuery
            ->whereIn('transaction_type', [
                'supplier_payment',
                'expense',
            ])
            ->sum('amount');

        return [
            'supplier' => $supplier,

            'total_expenses' =>
                $totalExpenses,

            'total_paid' =>
                $totalPaid,

            'outstanding' =>
                max($totalExpenses - $totalPaid, 0),
        ];
    }

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

    private function applyCategoryFilter(
        $query,
        array $filters
    ): void {
        if (!empty($filters['category'])) {
            $query->where(
                'category',
                $filters['category']
            );
        }
    }
}
