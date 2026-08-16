<?php

namespace App\Services;

use App\Models\EmployeeTransaction;
use App\Repositories\EmployeeTransactionRepository;

class EmployeeTransactionService
{
    public function __construct(
        protected EmployeeTransactionRepository $repository
    ) {}

    public function getAll()
    {
        return $this->repository->getAll();
    }

    public function create(array $data): EmployeeTransaction
    {
        return $this->repository->create($data);
    }

    public function update(
        EmployeeTransaction $transaction,
        array $data
    ): EmployeeTransaction {
        return $this->repository->update(
            $transaction,
            $data
        );
    }

    public function delete(
        EmployeeTransaction $transaction
    ): void {
        $this->repository->delete($transaction);
    }

    public function employeeTransactions(
        $employee,
        ?string $from = null,
        ?string $to = null
    ) {
        return $this->repository
            ->employeeTransactions(
                $employee,
                $from,
                $to
            );
    }
}
