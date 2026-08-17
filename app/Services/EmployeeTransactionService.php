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
        return $this->repository->create($this->prepareTransactionInfo($data));
    }

    public function update(
        EmployeeTransaction $transaction,
        array $data
    ): EmployeeTransaction {
        return $this->repository->update(
            $transaction,
            $this->prepareTransactionInfo($data)
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

    public function prepareTransactionInfo(array $attendance_request)
    {
        $attendance_data =  [
            'employee_id' => $attendance_request['employee_id'] ?? null,
            'type' => $attendance_request['type'] ?? null,
            'amount' => $attendance_request['amount'] ?? null,
            'transaction_date' => $attendance_request['transaction_date'] ?? now(),
            'description' => $attendance_request['description'] ?? null,
        ];

        return $attendance_data;
    }
}
