<?php

namespace App\Repositories\Inventory;

use App\Models\Inventory\Supplier;
use Carbon\Carbon;

class SupplierRepository
{
    public function getAll()
    {
        return Supplier::with([
            'cashTransactions',
            'purchases',
            'purchases.items',
        ])->get();
    }

    public function getDetails(Supplier $supplier): ?Supplier
    {
        return $supplier->load([
            'cashTransactions',
            'purchases',
            'purchases.items',
        ]);
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


    public function suppliersReport(
        ?string $from = null,
        ?string $to = null ): array
    {
        $supplier = Supplier::query();

        $purchasesQuery = $supplier->purchases()->items();

        $cashQuery = $supplier->cashTransactions();

        // if ($from) {
        //     $fromDate = Carbon::createFromFormat('m/Y', $from)->startOfMonth();

        //     $supplier->whereDate('revenue_date', '>=', $fromDate);
        // }

        // if ($to) {
        //     $toDate = Carbon::createFromFormat('m/Y', $to)->endOfMonth();

        //     $supplier->whereDate('revenue_date', '<=', $toDate);
        // }

        $totalPurchases = (float)
            $purchasesQuery->sum('total_amount');

        $totalPaid = (float) $cashQuery
            ->whereIn('transaction_type', [
                'supplier_payment',
                'expense',
            ])
            ->sum('amount');

        return [
            'supplier' => $supplier,

            'total_purchases' =>
                $totalPurchases,

            'total_paid' =>
                $totalPaid,

            'outstanding' =>
                max($totalPurchases - $totalPaid, 0),
        ];
    }
}
