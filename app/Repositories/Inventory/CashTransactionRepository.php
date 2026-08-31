<?php

namespace App\Repositories\Inventory;

use App\Models\Inventory\CashTransaction;
use Illuminate\Database\Eloquent\Collection;

class CashTransactionRepository
{
    public function create(array $data): CashTransaction
    {
        return CashTransaction::create($data);
    }

    public function findById(int $id): CashTransaction
    {
        return CashTransaction::query()
            ->with([
                'project',
                'supplier',
                'expense',
                'revenue',
                'employeePayment',
                'purchaseOrder',
            ])
            ->findOrFail($id);
    }

    public function getAll(): Collection
    {
        return CashTransaction::query()
            ->with([
                'project',
                'supplier',
                'expense',
                'revenue',
                'employeePayment',
                'purchaseOrder',
            ])
            ->latest('transaction_date')
            ->latest('id')
            ->get();
    }
}
