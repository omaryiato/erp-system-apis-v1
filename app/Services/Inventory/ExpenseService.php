<?php

namespace App\Services\Inventory;

use App\Models\Inventory\Expense;
use App\Repositories\Inventory\ExpenseRepository;
use Illuminate\Support\Facades\DB;

class ExpenseService
{
    public function __construct(
        private ExpenseRepository $repository
    ) {}

    public function getAll()
    {
        return $this->repository->getAll();
    }

    public function getDetails(int $id): ?Expense
    {
        return $this->repository->getDetails($id);
    }

    public function create(array $expense_request): Expense
    {
        return DB::transaction(
            fn () => $this->repository->create($this->prepareExpenseInfo($expense_request))
        );
    }

    public function update(
        Expense $expense,
        array $expense_request
    ): Expense {
        return DB::transaction(
            fn () => $this->repository->update(
                $expense,
                $this->prepareExpenseInfo($expense_request)
            )
        );
    }

    public function delete(
        Expense $expense
    ): bool {
        return DB::transaction(
            fn () => $this->repository->delete($expense)
        );
    }

    public function prepareExpenseInfo(array $expense_request)
    {

        $expense_data =  [
            'expense_number' => $expense_request['expense_number'] ?? null,
            'project_id' => $expense_request['project_id'] ?? null,
            'supplier_id' => $expense_request['supplier_id'] ?? null,
            'expense_date' => $expense_request['expense_date'] ?? now(),
            'category' => $expense_request['category'] ?? 0,
            'cost_price' => $expense_request['cost_price'] ?? null,
            'description' => $expense_request['description'] ?? null,
            'amount' => $expense_request['amount'] ?? null,
            'notes' => $expense_request['notes'] ?? null,
        ];

        return $expense_data;
    }
}
