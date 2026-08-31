<?php

namespace App\Repositories\Inventory;

use App\Models\Inventory\PurchaseItem;

class PurchaseItemRepository
{
    public function create(
        array $data
    ): PurchaseItem {
        return PurchaseItem::create($data);
    }

    public function find(
        int $id
    ): ?PurchaseItem {
        return PurchaseItem::query()
            ->with([
                'purchase',
                'item',
                'allocations.project',
            ])
            ->find($id);
    }

    public function update(
        PurchaseItem $purchaseItem,
        array $data
    ): PurchaseItem {
        $purchaseItem->update($data);

        return $purchaseItem->refresh();
    }

    public function delete(
        PurchaseItem $purchaseItem
    ): bool {
        return (bool) $purchaseItem->delete();
    }
}
