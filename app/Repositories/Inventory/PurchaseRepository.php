<?php

namespace App\Repositories\Inventory;

use App\Models\Inventory\Purchase;

class PurchaseRepository
{
    public function getAll()
    {
        return Purchase::query()
            ->with([
                'supplier',
                'items.item',
            ])
            ->latest('id');
    }

    public function getDetails(int $id): ?Purchase
    {
        return Purchase::query()
            ->with([
                'supplier',
                'items.item',
                'items.allocations.project',
            ])
            ->find($id);
    }

    public function create(array $data): Purchase
    {
        return Purchase::create($data);
    }

    public function update(
        Purchase $purchase,
        array $data
    ): Purchase {
        $purchase->update($data);

        return $purchase->refresh();
    }

    public function delete(Purchase $purchase): bool
    {
        return (bool) $purchase->delete();
    }
}
