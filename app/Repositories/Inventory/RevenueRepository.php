<?php

namespace App\Repositories\Inventory;

use App\Models\Inventory\Revenue;

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
}
