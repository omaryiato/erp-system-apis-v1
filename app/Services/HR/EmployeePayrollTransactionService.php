<?php

namespace App\Services\HR;

use App\Models\HR\EmployeePayrollTransaction;
use App\Repositories\HR\EmployeePayrollTransactionRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\ValidationException;

class EmployeePayrollTransactionService
{
    public function __construct(
        protected EmployeePayrollTransactionRepository $repository
    ) {}

    public function getAll(): Collection
    {
        return $this->repository->all();
    }

    public function find(int $id): EmployeePayrollTransaction
    {
        return $this->repository->find($id);
    }

    public function getEmployeeTransactions(
        int $employeeId
    ): Collection {
        return $this->repository->getEmployeeTransactions(
            $employeeId
        );
    }

    public function create(array $data): EmployeePayrollTransaction
    {
        if ($data['type'] === 'advance') {

            $data['remaining_amount'] =
                $data['amount'];

            if (
                isset($data['installment_amount']) &&
                $data['installment_amount'] > $data['amount']
            ) {
                throw ValidationException::withMessages([
                    'installment_amount' =>
                        'Installment amount cannot exceed advance amount.',
                ]);
            }
        } else {
            $data['remaining_amount'] = 0;
            $data['installment_amount'] = null;
        }

        return $this->repository->create($data);
    }

    public function update(
        EmployeePayrollTransaction $transaction,
        array $data
    ): EmployeePayrollTransaction {
        if ($transaction->status !== 'active') {
            throw ValidationException::withMessages([
                'status' =>
                    'Only active transactions can be updated.',
            ]);
        }

        return $this->repository->update(
            $transaction,
            $data
        );
    }

    public function cancel(
        EmployeePayrollTransaction $transaction
    ): EmployeePayrollTransaction {
        return $this->repository->update(
            $transaction,
            [
                'status' => 'cancelled',
            ]
        );
    }
}
