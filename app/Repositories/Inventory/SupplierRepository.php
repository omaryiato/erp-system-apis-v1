<?php

namespace App\Repositories\Inventory;

use App\Models\Inventory\Supplier;

class SupplierRepository
{
    public function getAll()
    {
        return Supplier::query()
            ->latest('id');
    }

    public function getDetails(int $id): ?Supplier
    {
        return Supplier::query()
            ->find($id);
    }

    public function create(array $supplier_request): Supplier
    {
        return Supplier::create($supplier_request);
    }

    public function update(
        Supplier $supplier,
        array $supplier_request
    ): Supplier {
        $supplier->update($supplier_request);

        return $supplier->refresh();
    }

    public function delete(Supplier $supplier): bool
    {
        return (bool) $supplier->delete();
    }
}
