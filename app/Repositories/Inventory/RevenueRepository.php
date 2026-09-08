<?php

namespace App\Repositories\Inventory;

use App\Models\Inventory\Revenue;
use Carbon\Carbon;

class RevenueRepository
{
    public function getAll() {
        return Revenue::with([
                'project',
            ])
            ->get();
    }

    public function getDetails(Revenue $revenue): ?Revenue
    {
        return $revenue->load([
                'project',
                'cashTransactions',
            ]);
    }

    public function create(array $revenue_request): Revenue
    {
        return Revenue::create($revenue_request);
    }

    public function update(
        Revenue $revenue,
        array $revenue_request
    ): Revenue {
        $revenue->update($revenue_request);

        return $revenue->refresh();
    }

    public function delete(
        Revenue $revenue
    ): bool {
        return (bool) $revenue->delete();
    }

    /*
    |--------------------------------------------------------------------------
    | Revenue Report
    |--------------------------------------------------------------------------
    */

    public function revenuesReport(
        ?string $from = null,
        ?string $to = null ): array
    {
        $query = Revenue::query();

        if ($from) {
            $fromDate = Carbon::createFromFormat('m/Y', $from)->startOfMonth();

            $query->whereDate('revenue_date', '>=', $fromDate);
        }

        if ($to) {
            $toDate = Carbon::createFromFormat('m/Y', $to)->endOfMonth();

            $query->whereDate('revenue_date', '<=', $toDate);
        }

        $revenues = $query
            ->with([
                'project',
                'cashTransactions',
            ])
            ->get();

        $total = (float) $revenues->sum('amount');

        $received = (float) $revenues->sum(
            fn (Revenue $revenue) =>
                $revenue->cashTransactions->sum('amount')
        );

        return [
            'total' => $total,
            'received' => $received,
            'outstanding' => max($total - $received, 0),
            'count' => $revenues->count(),
            'revenues' => $revenues,
        ];
    }

}
