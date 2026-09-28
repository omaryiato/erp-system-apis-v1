<?php

namespace App\Http\Resources\Inventory\Reports;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseReportResource extends JsonResource
{
    public function toArray(Request $request): array
    {

        return [
            'total' => (float) $this['total'],

            'count' => (int) $this['count'],

            'total_items' => (int) $this['total_items'],

            'total_quantity' => (float) $this['total_quantity'],

            'allocated_quantity' => (float) $this['allocated_quantity'],

            'unallocated_quantity' => (float) $this['unallocated_quantity'],

            'confirmed_count' => (int) $this['confirmed_count'],

            'draft_count' => (int) $this['draft_count'],

            'cancelled_count' => (int) $this['cancelled_count'],

            'purchases' => $this['purchases']->map(
                function ($purchase) {

                    return [
                        'id' => $purchase->id,

                        'reference_number' =>
                            $purchase->reference_number,

                        'purchase_date' =>
                            $purchase->purchase_date?->format('Y-m-d'),

                        'status' =>
                            $purchase->status,

                        'supplier' => [
                            'id' =>
                                $purchase->supplier?->id,

                            'name' =>
                                $purchase->supplier?->name,
                        ],

                        'notes' =>
                            $purchase->notes,

                        'total_amount' =>
                            (float) $purchase->items->sum(
                                'total_amount'
                            ),

                        'items' =>
                            $purchase->items->map(
                                function ($item) {

                                    $allocated =
                                        $item->allocations
                                            ->sum('quantity');

                                    return [
                                        'id' => $item->id,

                                        'item' => [
                                            'id' =>
                                                $item->item?->id,

                                            'name' =>
                                                $item->item?->name,
                                        ],

                                        'quantity' =>
                                            (float) $item->quantity,

                                        'unit_price' =>
                                            (float) $item->unit_price,

                                        'total_amount' =>
                                            (float) $item->total_amount,

                                        'payment_method' => $item->payment_method,
                                        'financial_account_id' => $item->financial_account_id ?? null,
                                        'financial_account_name' => $item->financialAccount?->name ?? null,

                                        'cheque_id' =>  $item->cheque_id ?? null,
                                        'cheque_number' =>  $item->cheque?->cheque_number ?? null,

                                        'allocated_quantity' =>
                                            (float) $allocated,

                                        'unallocated_quantity' =>
                                            max(
                                                (float) $item->quantity
                                                - (float) $allocated,
                                                0
                                            ),

                                        'allocations' =>
                                            $item->allocations->map(
                                                function ($allocation) {

                                                    return [
                                                        'id' =>
                                                            $allocation->id,

                                                        'project' => [
                                                            'id' =>
                                                                $allocation
                                                                    ->project
                                                                    ?->id,

                                                            'name' =>
                                                                $allocation
                                                                    ->project
                                                                    ?->name,
                                                        ],

                                                        'quantity' =>
                                                            (float)
                                                            $allocation
                                                                ->quantity,

                                                        'notes' =>
                                                            $allocation
                                                                ->notes,
                                                    ];
                                                }
                                            )->values(),
                                    ];
                                }
                            )->values(),
                    ];
                }
            )->values(),
        ];
    }
}

