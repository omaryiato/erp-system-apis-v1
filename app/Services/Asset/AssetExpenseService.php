<?php

namespace App\Services\Asset;

use App\Http\Repositories\Asset\AssetExpenseRepository;
use App\Http\Repositories\Asset\AssetRepository;
use App\Models\Asset\AssetExpense;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class AssetExpenseService
{
    protected AssetExpenseRepository $repository;
    protected AssetRepository $assetRepository;

    public function __construct(
        AssetExpenseRepository $repository,
        AssetRepository $assetRepository
    ) {
        $this->repository = $repository;
        $this->assetRepository = $assetRepository;
    }

    public function getAll()
    {
        return $this->repository->getAll();
    }

    public function getDetails(AssetExpense $assetExpense): AssetExpense
    {
        return $this->repository->getDetails($assetExpense);
    }



    public function create(array $expense_request): AssetExpense
    {
        return DB::transaction(function () use ($expense_request) {

            return $this->repository->create(
                $this->prepareExpenseInfo($expense_request)
            );
        });
    }

    public function update(
        AssetExpense $assetExpense,
        array $expense_request
    ): AssetExpense {
        return DB::transaction(function () use ($assetExpense, $expense_request) {

            return $this->repository->update(
                $assetExpense,
                $this->prepareExpenseInfo($expense_request)
            );
        });
    }

    public function delete(AssetExpense $assetExpense): bool
    {
        return DB::transaction(function () use ($assetExpense) {

            return $this->repository->delete($assetExpense);
        });
    }

    public function prepareExpenseInfo(array $expense_request)
    {
        $expense_data =  [
            'asset_id' => $expense_request['asset_id'] ?? null,
            'expense_date' => $expense_request['expense_date'] ?? null,
            'expense_type' => $expense_request['expense_type'] ?? null,
            'description' => $expense_request['description'] ?? null,
            'amount' => $expense_request['amount'] ?? null,
            'supplier_id' => $expense_request['supplier_id'] ?? null,
            'payment_method' => $expense_request['payment_method'] ?? null,
            'reference_number' => $expense_request['reference_number'] ?? null,
            'notes' => $expense_request['notes'] ?? null,
        ];

        return $expense_data;
    }
}
