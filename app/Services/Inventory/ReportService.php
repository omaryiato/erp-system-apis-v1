<?php

namespace App\Services\Inventory;

use App\Repositories\Inventory\ReportRepository;

class ReportService
{
    public function __construct(
        private ReportRepository $repository
    ) {}

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

    public function expenseReport(
        array $filters
    ): array {
        return $this->repository
            ->expenseReport($filters);
    }

    public function revenueReport(
        array $filters
    ): array {
        return $this->repository
            ->revenueReport($filters);
    }

    public function projectFinancial(
        int $projectId,
        array $filters
    ): array {
        return $this->repository
            ->projectFinancial(
                $projectId,
                $filters
            );
    }

    public function supplierFinancial(
        int $supplierId,
        array $filters
    ): array {
        return $this->repository
            ->supplierFinancial(
                $supplierId,
                $filters
            );
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
