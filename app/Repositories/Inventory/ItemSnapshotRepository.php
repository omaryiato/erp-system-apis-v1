<?php

namespace App\Repositories\Inventory;

use App\Models\Inventory\ItemSnapshot;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ItemSnapshotRepository
{
    public function paginateByItem(
        int $itemId,
        int $perPage = 20
    ): LengthAwarePaginator {
        return ItemSnapshot::query()
            ->with([
                'category:id,name',
                'changedBy:id,full_name',
            ])
            ->where('item_id', $itemId)
            ->latest('id')
            ->paginate($perPage);
    }

    public function find(int $id): ?ItemSnapshot
    {
        return ItemSnapshot::query()
            ->with([
                'category:id,name',
                'changedBy:id,full_name',
            ])
            ->find($id);
    }

    public function getSnapshots(int $item_id)
    {
        return ItemSnapshot::query()
            ->with(['item', 'category'])
            ->where('item_id', $item_id)->get();
    }
}
