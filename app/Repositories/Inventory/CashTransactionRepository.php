<?php

namespace App\Repositories\Inventory;

use App\Models\Inventory\CashTransaction;
use App\Models\Inventory\Expense;
use App\Models\Inventory\Purchase;
use App\Models\Inventory\Revenue;
use Illuminate\Database\Eloquent\Collection;

class CashTransactionRepository
{
    public function create(array $data): CashTransaction
    {
        return CashTransaction::create($data)->load(['financialAccount', 'cheque']);
    }

    public function findById(int $id): CashTransaction
    {
        return CashTransaction::query()
            ->with([
                'financialAccount',
                'cheque',
            ])
            ->findOrFail($id);
    }

    public function lockById(int $id): CashTransaction
    {
        return CashTransaction::query()->lockForUpdate()->findOrFail($id);
    }

    public function update(CashTransaction $transaction, array $data): CashTransaction
    {
        $transaction->update($data);

        return $transaction->load(['financialAccount', 'cheque']);
    }

    public function lockReferenceAmount(string $type, int $referenceId): string
    {
        $query = match ($type) {
            'expense' => Expense::query(),
            'revenue' => Revenue::query(),
            'purchase' => Purchase::query(),
        };

        return (string) $query->lockForUpdate()->findOrFail($referenceId)->amount;
    }

    public function sumForReference(string $type, int $referenceId, ?int $exceptId = null): string
    {
        return (string) CashTransaction::query()
            ->where('transaction_type', $type)
            ->where('reference_id', $referenceId)
            ->when($exceptId !== null, fn ($query) => $query->whereKeyNot($exceptId))
            ->sum('amount');
    }

    public function getAll(): Collection
    {
        return CashTransaction::query()
            ->with([
                'financialAccount',
                'cheque',
            ])
            ->latest('transaction_date')
            ->latest('id')
            ->get();
    }
}
