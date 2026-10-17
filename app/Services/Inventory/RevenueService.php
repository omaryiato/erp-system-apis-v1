<?php

namespace App\Services\Inventory;

use App\Models\Inventory\CashTransaction;
use App\Models\Inventory\Revenue;
use App\Repositories\Inventory\RevenueRepository;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class RevenueService
{
    public function __construct(
        private RevenueRepository $repository
    ) {}

    public function getAll()
    {
        return $this->repository->getAll();
    }

    public function getDetails(Revenue $revenue): ?Revenue
    {
        return $this->repository->getDetails($revenue);
    }

    public function create(array $revenue_request): Revenue
    {
        return DB::transaction(function () use ($revenue_request) {

            return $this->repository->create(
                $this->prepareRevenueInfo($revenue_request)
            );
        });
    }

    public function update(
        Revenue $revenue,
        array $revenue_request
    ): Revenue {
        return DB::transaction(function () use ($revenue_request, $revenue) {
            $revenueExists = $this->validateRevenuePayment($revenue);

            if ($revenueExists) {
                throw new RuntimeException(
                    'This revenue already has a payment transaction.'
                );
            }

            return $this->repository->update(
                $revenue,
                $this->prepareRevenueInfo($revenue_request)
            );
        });
    }

    public function delete(
        Revenue $revenue
    ): bool {
        return DB::transaction(function () use ($revenue) {
            $revenueExists = $this->validateRevenuePayment($revenue);

            if ($revenueExists) {
                throw new RuntimeException(
                    'This revenue already has a payment transaction.'
                );
            }

            return $this->repository->delete($revenue);
        });
    }

    private function validateRevenuePayment(Revenue $revenue): bool
    {
        return CashTransaction::where('revenue_id', $revenue->id)
            ->where('transaction_type', 'revenue')
            ->exists();
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

    public function revenuesReport(
        array $filters
    ): array {

        $from = $filters['from'] ?? null;
        $to = $filters['to'] ?? null;

        return $this->repository
            ->revenuesReport($from, $to);
    }

}
