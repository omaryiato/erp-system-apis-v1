<?php

namespace App\Services\Inventory;

use App\Models\Inventory\ItemSnapshot;
use App\Repositories\Inventory\ItemSnapshotRepository;

class ItemSnapshotService
{
    public function __construct(
        private ItemSnapshotRepository $repository
    ) {}

    public function paginateByItem(
        int $itemId,
        int $perPage = 20
    ) {
        return $this->repository->paginateByItem(
            $itemId,
            $perPage
        );
    }

    public function find(int $id): ?ItemSnapshot
    {
        return $this->repository->find($id);
    }

    public function getSnapshots(int $item_id)
    {
        return $this->repository->getSnapshots($item_id) ;
    }
}
