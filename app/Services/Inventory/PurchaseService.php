<?php

namespace App\Services\Inventory;

use App\Models\Inventory\Purchase;
use App\Repositories\Inventory\PurchaseRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PurchaseService
{
    public function __construct(
        private PurchaseRepository $repository
    ) {}

    public function getAll()
    {
        return $this->repository->getAll();
    }

    public function getDetails(Purchase $purchase): ?Purchase
    {
        return $this->repository->getDetails($purchase);
    }

    public function create(array $purchase_request): Purchase
    {
        return DB::transaction(function () use ($purchase_request) {

            $purchase_items = $purchase_request['items'] ?? [];

            unset($purchase_request['items']);

            $purchase = $this->repository->create($this->preparePurchaseInfo($purchase_request));

            foreach ($purchase_items as $purchase_items_data) {

                $purchase_item = $purchase->items()->create( $this->preparePurchaseItemInfo($purchase_items_data) );

                foreach (
                    $purchase_items_data['allocations'] ?? []
                    as $allocation
                ) {
                    // $allocation['purchase_item_id'] = $purchase_item->id;
                    $this->createAllocation( $purchase_item, $this->preparePurchaseAllocationInfo($allocation) );
                }
            }

            return $purchase->load([
                'supplier',
                'items.item',
                'items.allocations.project',
            ]);
        });
    }

    private function createAllocation(
        $purchaseItem,
        array $allocation
    ) {
        $allocatedQuantity = $purchaseItem
            ->allocations()
            ->sum('quantity');

        $requested_quantity = $allocation['quantity'];

        $remaining =
            $purchaseItem->quantity -
            $allocatedQuantity;

        if ($requested_quantity > $remaining) {
            throw ValidationException::withMessages([
                'quantity' => [
                    "The requested allocation quantity ({$requested_quantity}) exceeds the remaining quantity ({$remaining})."
                ],
            ]);
        }

        return $purchaseItem->allocations()->create($allocation);
    }

    public function preparePurchaseInfo(array $purchase_request)
    {

        $purchase_data =  [
            'supplier_id' => $purchase_request['supplier_id'] ?? null,
            'purchase_date' => $purchase_request['purchase_date'] ?? now(),
            'reference_number' => $purchase_request['reference_number'] ?? null,
            'notes' => $purchase_request['notes'] ?? null,
        ];

        return $purchase_data;
    }

    public function preparePurchaseItemInfo(array $purchase_item_request)
    {
        $purchase_item_data =  [
            'item_id' => $purchase_item_request['item_id'] ?? null,
            'quantity' => $purchase_item_request['quantity'] ?? null,
            'unit_price' => $purchase_item_request['unit_price'] ?? null,
            'total_amount' => $purchase_item_request['total_amount'] ?? null,
            'notes' => $purchase_item_request['notes'] ?? null,
        ];

        return $purchase_item_data;
    }

    public function preparePurchaseAllocationInfo(array $purchase_allocation_request)
    {

        $purchase_allocation_data =  [
            'purchase_item_id' => $purchase_allocation_request['purchase_item_id'] ?? null,
            'project_id' => $purchase_allocation_request['project_id'] ?? null,
            'quantity' => $purchase_allocation_request['quantity'] ?? null,
            'notes' => $purchase_allocation_request['notes'] ?? null,
        ];

        return $purchase_allocation_data;
    }

}
