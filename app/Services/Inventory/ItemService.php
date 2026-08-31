<?php

namespace App\Services\Inventory;

use App\Models\Inventory\Item;
use App\Models\Inventory\ItemSnapshot;
use App\Repositories\Inventory\ItemRepository;
use Illuminate\Support\Facades\DB;

class ItemService
{
    public function __construct(
        private ItemRepository $repository
    ) {}

    public function getAll()
    {
        return $this->repository->getAll();
    }

    public function getDetails(int $id): ?Item
    {
        return $this->repository->getDetails($id);
    }

    public function create(array $item_request): Item
    {
        return DB::transaction(function () use ($item_request) {

            return $this->repository->create($this->prepareItemInfo($item_request));
        });
    }

    public function update(
        Item $item,
        array $item_request
    ): Item {
        return DB::transaction(function () use (
            $item,
            $item_request
        ) {

            return $this->repository->update(
                $item,
                $this->prepareItemInfo($item_request)
            );
        });
    }

    public function delete(Item $item): bool
    {
        return DB::transaction(
            fn () => $this->repository->delete($item)
        );
    }

    public function prepareItemInfo(array $item_request)
    {

        $item_data =  [
            'category_id' => $item_request['category_id'] ?? null,
            'name' => $item_request['name'] ?? null,
            'code' => $item_request['code'] ?? null,
            'unit' => $item_request['unit'] ?? null,
            'quantity' => $item_request['quantity'] ?? 0,
            'cost_price' => $item_request['cost_price'] ?? null,
            'sale_price' => $item_request['sale_price'] ?? null,
            'minimum_stock' => $item_request['minimum_stock'] ?? null,
            'status' => $item_request['status'] ?? 'active',
        ];

        return $item_data;
    }

}
