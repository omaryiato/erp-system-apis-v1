<?php

namespace App\Repositories\Inventory;

use App\Models\Inventory\Purchase;

class PurchaseRepository
{
    public function getAll()
    {
        return Purchase::with([
                'supplier',
                'items.item',
                'items.allocations.project'
            ])
            ->get();
    }

    public function getDetails(Purchase $purchase): ?Purchase
    {
        return $purchase->load([
                'supplier',
                'items.item',
                'items.allocations.project',
            ]);
    }

    public function create(array $purchase_request): Purchase
    {
        return Purchase::create($purchase_request);
    }

    public function update(
        Purchase $purchase,
        array $purchase_request
    ): Purchase {
        $purchase->update($purchase_request);

        return $purchase->refresh();
    }

    public function delete(Purchase $purchase): bool
    {
        return (bool) $purchase->delete();
    }
}
