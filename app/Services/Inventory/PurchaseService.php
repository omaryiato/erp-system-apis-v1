<?php

namespace App\Services\Inventory;

use App\Models\Inventory\Purchase;
use App\Repositories\Inventory\PurchaseRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Services\ChequeService;
use App\Services\FinancialAccountService;

class PurchaseService
{
    public function __construct(
        private PurchaseRepository $repository,
        protected ChequeService $chequeService,
        protected FinancialAccountService $financialAccountService,
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
                $cheque_info = null;

                if (isset($purchase_items_data['payment_method']) && $purchase_items_data['payment_method'] == 'cheques' ) {

                    $cheque_info = $this->chequeService->create(
                        $purchase_items_data['cheque']
                    );

                    $purchase_items_data['cheques_id'] = $cheque_info->id;
                    $purchase_items_data['amount'] = $cheque_info->amount;
                }

                $purchase_items_info = $this->preparePurchaseItemInfo($purchase_items_data);

                $purchase_item = $purchase->items()->create( $purchase_items_info );

                $this->financialAccountService->updateAccountBalance($purchase_items_info, "purchase");

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


    public function update(Purchase $purchase, array $purchase_request): Purchase
    {
        return DB::transaction(function () use ($purchase, $purchase_request) {

            if (!$purchase) {
                throw new \Exception('Purchase not found.');
            }

            $purchase_items = $purchase_request['items'] ?? [];

            unset($purchase_request['items']);

            /*
            * 1. Reverse old financial transactions
            */
            foreach ($purchase->items as $oldItem) {

                // $this->financialAccountService->reverseAccountBalance(
                //     $oldItem,
                //     'purchase'
                // );

                /*
                * Delete old allocations
                */
                $oldItem->allocations()->delete();

                /*
                * Delete old cheque
                */
                if ($oldItem->cheques_id) {
                    $this->chequeService->delete($oldItem->cheques_id);
                }
            }

            /*
            * 2. Delete old purchase items
            */
            $purchase->items()->delete();

            /*
            * 3. Update purchase
            */
            $purchase->update(
                $this->preparePurchaseInfo($purchase_request)
            );

            /*
            * 4. Create new items
            */
            foreach ($purchase_items as $purchase_items_data) {

                $cheque_info = null;

                if (
                    isset($purchase_items_data['payment_method']) &&
                    $purchase_items_data['payment_method'] === 'cheques'
                ) {

                    $cheque_info = $this->chequeService->create(
                        $purchase_items_data['cheque']
                    );

                    $purchase_items_data['cheques_id'] = $cheque_info->id;
                    $purchase_items_data['amount'] = $cheque_info->amount;
                }

                $purchase_items_info = $this->preparePurchaseItemInfo(
                    $purchase_items_data
                );

                $purchase_item = $purchase->items()->create(
                    $purchase_items_info
                );

                /*
                * Apply new financial transaction
                */
                $this->financialAccountService->updateAccountBalance(
                    $purchase_items_info,
                    'purchase'
                );

                /*
                * Create allocations
                */
                foreach (
                    $purchase_items_data['allocations'] ?? []
                    as $allocation
                ) {
                    $this->createAllocation(
                        $purchase_item,
                        $this->preparePurchaseAllocationInfo($allocation)
                    );
                }
            }

            return $purchase->load([
                'supplier',
                'items.item',
                'items.allocations.project',
            ]);
        });
    }


    public function delete(Purchase $purchase): bool
    {
        return DB::transaction(function () use ($purchase) {

            if (!$purchase) {
                throw new \Exception('Purchase not found.');
            }

            /*
            * 1. Reverse financial transactions
            */
            foreach ($purchase->items as $item) {

                // $this->financialAccountService->reverseAccountBalance(
                //     $item,
                //     'purchase'
                // );

                /*
                * 2. Delete allocations
                */
                $item->allocations()->delete();

                /*
                * 3. Delete cheque
                */
                if ($item->cheques_id) {
                    $this->chequeService->delete($item->cheques_id);
                }
            }

            /*
            * 4. Delete purchase items
            */
            $purchase->items()->delete();

            /*
            * 5. Delete purchase
            */
            return $purchase->delete();
        });
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
            'purchase_type' => $purchase_item_request['purchase_type'] ?? 'cash',
            'total_amount' => $purchase_item_request['total_amount'] ?? null,
            'notes' => $purchase_item_request['notes'] ?? null,
            'cheques_id' => $purchase_item_request['cheques_id'] ?? null,
            'financial_account_id' => $purchase_item_request['financial_account_id'] ?? null,
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

    public function purchasesReport(
        array $filters
    ): array {

        $from = $filters['from'] ?? null;
        $to = $filters['to'] ?? null;

        return $this->repository
            ->purchasesReport($from, $to);
    }


}
