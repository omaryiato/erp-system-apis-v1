<?php

namespace App\Services\Inventory;

use App\Models\Attendance\EmployeePayment;
use App\Models\Inventory\CashTransaction;
use App\Models\Inventory\Expense;
use App\Models\Inventory\Revenue;
use App\Repositories\Inventory\CashTransactionRepository;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use RuntimeException;

class CashTransactionService
{
    public function __construct(
        private CashTransactionRepository $repository
    ) {}

    public function create(array $data): CashTransaction
    {
        return DB::transaction(function () use ($data) {

            $type = $data['transaction_type'];

            $this->validateTransactionType(
                $data
            );

            $this->validateAmount(
                $data
            );

            $data['transaction_number'] =
                $this->generateTransactionNumber();

            // $data['created_by'] =
            //     auth()->id();

            // $data['updated_by'] =
            //     auth()->id();

            return $this->repository->create(
                $data
            );
        });
    }

    private function validateTransactionType(
        array $data
    ): void {

        $type = $data['transaction_type'];

        switch ($type) {

            case 'revenue_payment':

                if (empty($data['revenue_id'])) {
                    throw new InvalidArgumentException(
                        'Revenue ID is required.'
                    );
                }

                $this->validateRevenuePayment(
                    $data
                );

                break;

            case 'expense_payment':

                if (empty($data['expense_id'])) {
                    throw new InvalidArgumentException(
                        'Expense ID is required.'
                    );
                }

                $this->validateExpensePayment(
                    $data
                );

                break;

            case 'supplier_payment':

                if (empty($data['supplier_id'])) {
                    throw new InvalidArgumentException(
                        'Supplier ID is required.'
                    );
                }

                break;

            case 'employee_payment':

                if (empty($data['employee_payment_id'])) {
                    throw new InvalidArgumentException(
                        'Employee payment ID is required.'
                    );
                }

                $this->validateEmployeePayment(
                    $data
                );

                break;

            case 'other_income':
            case 'other_expense':

                break;

            default:

                throw new InvalidArgumentException(
                    'Invalid transaction type.'
                );
        }
    }

    private function validateAmount(
        array $data
    ): void {

        if (
            !isset($data['amount']) ||
            $data['amount'] <= 0
        ) {
            throw new InvalidArgumentException(
                'Amount must be greater than zero.'
            );
        }
    }

    private function validateRevenuePayment(
        array $data
    ): void {

        $revenue = Revenue::query()
            ->with('cashTransactions')
            ->lockForUpdate()
            ->findOrFail(
                $data['revenue_id']
            );

        $received = $revenue
            ->cashTransactions
            ->sum('amount');

        $remaining =
            (float) $revenue->amount -
            (float) $received;

        if (
            (float) $data['amount'] >
            $remaining
        ) {
            throw new RuntimeException(
                'Payment amount exceeds remaining revenue amount.'
            );
        }

        if ($remaining <= 0) {
            throw new RuntimeException(
                'This revenue has already been fully paid.'
            );
        }

        if (
            empty($data['project_id']) &&
            $revenue->project_id
        ) {
            $data['project_id'] =
                $revenue->project_id;
        }
    }

    private function validateExpensePayment(
        array $data
    ): void {

        $expense =  Expense::query()
            ->with('cashTransactions')
            ->lockForUpdate()
            ->findOrFail(
                $data['expense_id']
            );

        $paid = $expense
            ->cashTransactions
            ->sum('amount');

        $remaining =
            (float) $expense->amount -
            (float) $paid;

        if (
            (float) $data['amount'] >
            $remaining
        ) {
            throw new RuntimeException(
                'Payment amount exceeds remaining expense amount.'
            );
        }

        if ($remaining <= 0) {
            throw new RuntimeException(
                'This expense has already been fully paid.'
            );
        }

        if (
            empty($data['project_id']) &&
            $expense->project_id
        ) {
            $data['project_id'] =
                $expense->project_id;
        }
    }

    private function validateEmployeePayment(
        array $data
    ): void {

        $payment = EmployeePayment::query()
                ->findOrFail(
                    $data['employee_payment_id']
                );

        if (
            (float) $data['amount'] !=
            (float) $payment->amount
        ) {
            throw new RuntimeException(
                'Cash transaction amount must match employee payment amount.'
            );
        }
    }

    private function generateTransactionNumber(): string
    {
        $last = CashTransaction::query()
            ->latest('id')
            ->value('id');

        return 'TRX-' .
            str_pad(
                ($last ?? 0) + 1,
                6,
                '0',
                STR_PAD_LEFT
            );
    }
}
