<?php

namespace App\Repositories;

use App\Models\Asset\AssetExpense;
use App\Models\Attendance\Employee;
use App\Models\Attendance\EmployeePayment;
use App\Models\FinancialAccount;
use App\Models\Inventory\Expense;
use App\Models\Inventory\Project;
use App\Models\Inventory\Purchase;
use App\Models\Inventory\Revenue;
use App\Models\Inventory\Supplier;

class FinancialAccountRepository
{
    public function getAll()
    {
        return FinancialAccount::with(
            [
                'cheques',
                'cashTransactions',
                // 'purchaseItems',
                'employeePayments',
                'assetExpenses',
            ])->get();

    }

    public function getDetails(FinancialAccount $account): ?FinancialAccount
    {
        return $account->load([
                'cheques',
                'cashTransactions',
                // 'purchaseItems',
                'employeePayments',
                'assetExpenses',
            ]);
    }

    public function create(array $account_request): FinancialAccount
    {
        return FinancialAccount::create($account_request);
    }

    public function update(
        FinancialAccount $account,
        array $account_request
    ): FinancialAccount {
        $account->update($account_request);

        return $account->refresh();
    }

    public function delete(FinancialAccount $account): bool
    {
        return $account->delete();
    }

    public function hasCheques(FinancialAccount $account): bool
    {
        return $account->cheques()->exists();
    }

    public function updateAccountBalance(
        FinancialAccount $account,
        array $account_request
    ): FinancialAccount {
        $account->update($account_request);

        return $account->refresh();
    }

    public function fullReport(): array
    {


        /*
        |--------------------------------------------------------------------------
        | Revenue
        |--------------------------------------------------------------------------
        */

        $revenue = Revenue::query();

        $revenues = $revenue
            ->with([
                'cashTransactions',
            ])
            ->get();

        $total_revenues = (float) $revenues->sum('amount');

        $received_revenues = (float) $revenues->sum(
            fn (Revenue $revenue) =>
                $revenue->cashTransactions->sum('amount')
        );

        /*
        |--------------------------------------------------------------------------
        | Expense
        |--------------------------------------------------------------------------
        */

        $expense = Expense::query();

        $expenses = $expense
            ->with([
                'cashTransactions',
            ])
            ->get();

        $total_expenses = (float) $expenses->sum('amount');

        $paid_expenses = (float) $expenses->sum(
            fn (Expense $expense) =>
                $expense->cashTransactions->sum('amount')
        );

        /*
        |--------------------------------------------------------------------------
        | Purchase
        |--------------------------------------------------------------------------
        */

        $purchase = Purchase::query();


        $purchases = $purchase
            ->with([
                'cashTransactions',
            ])
            ->get();

        $total_purchases = (float) $purchases->sum('amount');

        $paid_purchases = (float) $purchases->sum(
            fn (Expense $purchase) =>
                $purchase->cashTransactions->sum('amount')
        );


        /*
        |--------------------------------------------------------------------------
        | Purchase
        |--------------------------------------------------------------------------
        */

        $assetExpenses = AssetExpense::query();

        $total_asset_expenses = (float) $assetExpenses->sum('amount');

        /*
        |--------------------------------------------------------------------------
        | employee
        |--------------------------------------------------------------------------
        */


        $employeeReport = $this->employeeReport();


        // $supplier = Supplier::query();
        // $project = Project::query();

        return [
            'total_revenues' => $total_revenues,
            'received_revenues' => $received_revenues,
            'remaining_revenues' => max($total_revenues - $received_revenues, 0),

            'total_expenses' => $total_expenses,
            'paid_expenses' => $paid_expenses,
            'remaining_expenses' => max($total_expenses - $paid_expenses, 0),

            'total_purchases' => $total_purchases ,
            'paid_purchases' => $paid_purchases ,
            'remaining_purchases' =>max($total_purchases - $paid_purchases, 0),

            'total_asset_expenses' => $total_asset_expenses ,
            'paid_asset_expenses' => $total_asset_expenses ,
            'remaining_asset_expenses' =>max($total_asset_expenses - $total_asset_expenses, 0),

            'total_employee_expenses' => $employeeReport['total_employee_expenses'] ,
            'paid_employee_expenses' => $employeeReport['paid_employee_expenses'] ,
            'remaining_employee_expenses' => $employeeReport['remaining_employee_expenses'],
        ];
    }

    public function employeeReport(): array
    {
        $employees = Employee::with(['attendanceHistory', 'payments'])->get();

        $totals = [
            'total' => 0,
            'remaining' => 0,
            'paid' => 0,
        ];

        foreach ($employees as $employee) {
            $attendance = $employee->attendanceHistory;
            $payments = $employee->payments;

            $basicAmount = $attendance->sum('daily_amount');
            $overtimeAmount = $attendance->sum('overtime_amount');

            $advances = $payments
                ->where('payment_type', 'advance')
                ->sum('amount');

            $deductions = $payments
                ->where('payment_type', 'deduction')
                ->sum('amount');

            $salary = $payments
                ->where('payment_type', 'salary')
                ->sum('amount');

            $other = $payments
                ->where('payment_type', 'other')
                ->sum('amount');

            $totalEarned = $basicAmount + $overtimeAmount;

            $netDue = $totalEarned - $advances - $salary - $other - $deductions;

            $totals['total'] += $netDue;
            $totals['paid'] += $salary;
        }

        $totals['remaining'] = $totals['total'] - $totals['paid'];

        return [
            'total_employee_expenses' => round($totals['total'], 2),
            'remaining_employee_expenses' => round($totals['remaining'], 2),
            'paid_employee_expenses' => round($totals['paid'], 2),
        ];
    }
}
