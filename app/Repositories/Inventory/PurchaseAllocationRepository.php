<?php

namespace App\Repositories\Inventory;

use App\Models\Inventory\PurchaseAllocation;

class PurchaseAllocationRepository
{
    public function create(
        array $data
    ): PurchaseAllocation {
        return PurchaseAllocation::create($data);
    }

    public function find(
        int $id
    ): ?PurchaseAllocation {
        return PurchaseAllocation::query()
            ->with([
                'purchaseItem.item',
                'project',
            ])
            ->find($id);
    }

    public function update(
        PurchaseAllocation $allocation,
        array $data
    ): PurchaseAllocation {
        $allocation->update($data);

        return $allocation->refresh();
    }

    public function delete(
        PurchaseAllocation $allocation
    ): bool {
        return (bool) $allocation->delete();
    }
}
