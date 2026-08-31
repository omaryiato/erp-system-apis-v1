<?php

namespace App\Services\Inventory;

use App\Models\Inventory\Revenue;
use App\Repositories\Inventory\RevenueRepository;
use Illuminate\Support\Facades\DB;

class RevenueService
{
    public function __construct(
        private RevenueRepository $repository
    ) {}

    public function getAll()
    {
        return $this->repository->getAll();
    }

    public function getDetails(int $id): ?Revenue
    {
        return $this->repository->getDetails($id);
    }

    public function create(array $revenue_request): Revenue
    {
        return DB::transaction(
            fn () => $this->repository->create($this->prepareRevenueInfo($revenue_request))
        );
    }

    public function update(
        Revenue $revenue,
        array $revenue_request
    ): Revenue {
        return DB::transaction(
            fn () => $this->repository->update(
                $revenue,
                $this->prepareRevenueInfo($revenue_request)
            )
        );
    }

    public function delete(
        Revenue $revenue
    ): bool {
        return DB::transaction(
            fn () => $this->repository->delete($revenue)
        );
    }

    public function prepareRevenueInfo(array $revenue_request)
    {

        $revenue_data =  [
            'revenue_number' => $revenue_request['revenue_number'] ?? null,
            'project_id' => $revenue_request['project_id'] ?? null,
            'revenue_date' => $revenue_request['revenue_date'] ?? now(),
            'category' => $revenue_request['category'] ?? 0,
            'description' => $revenue_request['description'] ?? null,
            'amount' => $revenue_request['amount'] ?? null,
            'notes' => $revenue_request['notes'] ?? null,
        ];

        return $revenue_data;
    }
}
