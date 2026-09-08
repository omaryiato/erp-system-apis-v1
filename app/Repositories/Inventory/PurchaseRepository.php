<?php

namespace App\Repositories\Inventory;

use App\Models\Inventory\Purchase;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class PurchaseRepository
{
    public function getAll()
    {
        return Purchase::with([
                'supplier',
                'items.item',
                'items.allocations.project'
            ])
            ->get();
    }

    public function getDetails(Purchase $purchase): ?Purchase
    {
        return $purchase->load([
                'supplier',
                'items.item',
                'items.allocations.project',
            ]);
    }

    public function create(array $purchase_request): Purchase
    {
        return Purchase::create($purchase_request);
    }

    public function update(
        Purchase $purchase,
        array $purchase_request
    ): Purchase {
        $purchase->update($purchase_request);

        return $purchase->refresh();
    }

    public function delete(Purchase $purchase): bool
    {
        return (bool) $purchase->delete();
    }

    public function purchasesReport(
        ?string $from = null,
        ?string $to = null
    ): array {

        $query = Purchase::query();

        if ($from) {
            $fromDate = Carbon::createFromFormat(
                'm/Y',
                $from
            )->startOfMonth();

            $query->whereDate(
                'purchase_date',
                '>=',
                $fromDate
            );
        }

        if ($to) {
            $toDate = Carbon::createFromFormat(
                'm/Y',
                $to
            )->endOfMonth();

            $query->whereDate(
                'purchase_date',
                '<=',
                $toDate
            );
        }

        $purchases = $query
            ->with([
                'supplier',
                'items.item',
                'items.allocations.project',
            ])
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Invoice totals
        |--------------------------------------------------------------------------
        */

        $total = (float) $purchases->sum(
            fn (Purchase $purchase) =>
                $purchase->items->sum('total_amount')
        );

        /*
        |--------------------------------------------------------------------------
        | Total quantity
        |--------------------------------------------------------------------------
        */

        $totalQuantity = (float) $purchases->sum(
            fn (Purchase $purchase) =>
                $purchase->items->sum('quantity')
        );

        /*
        |--------------------------------------------------------------------------
        | Total items
        |--------------------------------------------------------------------------
        */

        $totalItems = $purchases->sum(
            fn (Purchase $purchase) =>
                $purchase->items->count()
        );

        /*
        |--------------------------------------------------------------------------
        | Allocated quantity
        |--------------------------------------------------------------------------
        */

        $allocatedQuantity = (float) $purchases->sum(
            fn (Purchase $purchase) =>
                $purchase->items->sum(
                    fn ($item) =>
                        $item->allocations->sum('quantity')
                )
        );

        /*
        |--------------------------------------------------------------------------
        | Unallocated quantity
        |--------------------------------------------------------------------------
        */

        $unallocatedQuantity = max(
            $totalQuantity - $allocatedQuantity,
            0
        );

        /*
        |--------------------------------------------------------------------------
        | Confirmed / Draft / Cancelled
        |--------------------------------------------------------------------------
        */

        $confirmedCount = $purchases->where(
            'status',
            'confirmed'
        )->count();

        $draftCount = $purchases->where(
            'status',
            'draft'
        )->count();

        $cancelledCount = $purchases->where(
            'status',
            'cancelled'
        )->count();

        return [
            'total' => $total,

            'count' => $purchases->count(),

            'total_items' => $totalItems,

            'total_quantity' => $totalQuantity,

            'allocated_quantity' => $allocatedQuantity,

            'unallocated_quantity' => $unallocatedQuantity,

            'confirmed_count' => $confirmedCount,

            'draft_count' => $draftCount,

            'cancelled_count' => $cancelledCount,

            'purchases' => $purchases,
        ];
    }

}
