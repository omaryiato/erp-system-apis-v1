<?php

namespace App\Services\Asset;

use App\Repositories\Asset\AssetExpenseRepository;
use App\Repositories\Asset\AssetRepository;
use App\Models\Asset\AssetExpense;
use Illuminate\Support\Facades\DB;
use App\Services\ChequeService;
use App\Services\FinancialAccountService;

class AssetExpenseService
{
    protected AssetExpenseRepository $repository;
    protected AssetRepository $assetRepository;

    public function __construct(
        AssetExpenseRepository $repository,
        AssetRepository $assetRepository,
        protected ChequeService $chequeService,
        protected FinancialAccountService $financialAccountService,
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

            $cheque_info = null;

            if (isset($expense_request['payment_method']) &&
                $expense_request['payment_method'] == 'cheques' &&
                !isset($expense_request['cheque_id'])  ) {

                $cheque_info = $this->chequeService->create(
                    $expense_request['cheque']
                );

                $expense_request['cheques_id'] = $cheque_info->id;
                $expense_request['cheque_amount'] = $cheque_info->amount;
            }

            $expense_info = $this->prepareExpenseInfo($expense_request);

            $expense_details = $this->repository->create($expense_info);

            // $this->financialAccountService->updateAccountBalance($expense_info, "asset_expense");

            return $expense_details;
        });
    }

    public function update(
        AssetExpense $assetExpense,
        array $expense_request
    ): AssetExpense {
        return DB::transaction(function () use ($assetExpense, $expense_request) {

            $this->chequeService->delete(
                    $assetExpense->cheque_id
                );

            $cheque_info = null;

            if (isset($expense_request['payment_method']) &&
                $expense_request['payment_method'] == 'cheques' &&
                !isset($expense_request['cheque_id']) ) {

                $cheque_info = $this->chequeService->create(
                    $expense_request['cheque']
                );

                $expense_request['cheques_id'] = $cheque_info->id;
                $expense_request['cheque_amount'] = $cheque_info->amount;
            }

            $expense_info = $this->prepareExpenseInfo($expense_request);

            $expense_details =  $this->repository->update($assetExpense, $expense_request);

            // $this->financialAccountService->updateAccountBalance($expense_info, "asset_expense");

            return $expense_details;
        });
    }

    public function delete(AssetExpense $assetExpense): bool
    {
        return DB::transaction(function () use ($assetExpense) {

            if(isset($assetExpense->cheque_id)){
                $this->chequeService->delete(
                        $assetExpense->cheque_id
                    );
            }

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
            'cheques_id' => $expense_request['cheques_id'] ?? null,
            'cheque_amount' => $expense_request['cheque_amount'] ?? null,
            'financial_account_id' => $expense_request['financial_account_id'] ?? null,
        ];

        return $expense_data;
    }
}
