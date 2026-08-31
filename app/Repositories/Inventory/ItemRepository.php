<?php

namespace App\Repositories\Inventory;

use App\Models\Inventory\Item;

class ItemRepository
{
    public function getAll()
    {
        return Item::query()
            ->with('category')
            ->latest('id');
    }

    public function getDetails(int $id): ?Item
    {
        return Item::query()
            ->with('category')
            ->find($id);
    }

    public function create(array $item_request): Item
    {
        return Item::create($item_request);
    }

    public function update(
        Item $item,
        array $item_request
    ): Item {
        $item->update($item_request);

        return $item->refresh();
    }

    public function delete(Item $item): bool
    {
        return (bool) $item->delete();
    }


}
