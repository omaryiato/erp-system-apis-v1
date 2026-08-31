<?php

namespace App\Repositories\Inventory;

use App\Models\Inventory\Revenue;

class RevenueRepository
{
    public function getAll() {
        return Revenue::query()
            ->with([
                'project',
            ])
            ->latest('id');
    }

    public function getDetails(int $id): ?Revenue
    {
        return Revenue::query()
            ->with([
                'project',
                'cashTransactions',
            ])
            ->find($id);
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
