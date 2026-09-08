<?php

namespace App\Services\Inventory;

use App\Repositories\Inventory\ReportRepository;

class ReportService
{
    public function __construct(
        private ReportRepository $repository
    ) {}

    /*
    |--------------------------------------------------------------------------
    | Operation Report
    |--------------------------------------------------------------------------
    */
    
    public function operationReport(
        array $filters
    ): array {

        $from = $filters['from'] ?? null;
        $to = $filters['to'] ?? null;

        return $this->repository
            ->operationReport($from, $to);
    }

    public function financialSummary(
        array $filters
    ): array {
        return $this->repository
            ->financialSummary($filters);
    }

    public function cashFlow(
        array $filters
    ): array {
        return $this->repository
            ->cashFlow($filters);
    }



    public function outstandingExpenses(
        array $filters
    ) {
        return $this->repository
            ->outstandingExpenses($filters);
    }

    public function outstandingRevenues(
        array $filters
    ) {
        return $this->repository
            ->outstandingRevenues($filters);
    }

    public function cashTransactions(
        array $filters,
        int $perPage = 20
    ) {
        return $this->repository
            ->cashTransactions(
                $filters,
                $perPage
            );
    }

    public function monthlyFinancial(
        array $filters
    ) {
        return $this->repository
            ->monthlyFinancial($filters);
    }
}
