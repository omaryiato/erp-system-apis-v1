<?php

namespace App\Services;

use App\Models\FinancialAccount;
use App\Repositories\FinancialAccountRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\ValidationException;

class FinancialAccountService
{
    public function __construct(
        protected FinancialAccountRepository $repository
    ) {
    }

    public function getAll()
    {
        return $this->repository->getAll();
    }

    public function getDetails(FinancialAccount $account): FinancialAccount
    {
        return $this->repository->getDetails($account);
    }

    public function create(array $account_request): FinancialAccount
    {
        return $this->repository->create($this->prepareFinancialAccountInfo($account_request));
    }

    public function update(
        FinancialAccount $account,
        array $account_request
    ): FinancialAccount {

        return $this->repository->update(
            $account,
            $this->prepareFinancialAccountInfo($account_request)
        );
    }

    public function delete(FinancialAccount $account): bool
    {

        if ($this->repository->hasCheques($account)) {
            throw ValidationException::withMessages([
                'financial_account' => [
                    'Cannot delete a financial account that has cheques.'
                ],
            ]);
        }

        return $this->repository->delete($account);
    }


    public function prepareFinancialAccountInfo(array $account_request)
    {

        $account_data =  [
            'name' => $account_request['name'] ?? null,
            'name_ar' => $account_request['name_ar'] ?? null,
            'account_type' => $account_request['account_type'] ?? now(),
            'account_number' => $account_request['account_number'] ?? 0,
            'iban' => $account_request['iban'] ?? null,
            'bank_name' => $account_request['bank_name'] ?? null,
            'currency' => $account_request['currency'] ?? null,
            'opening_balance' => $account_request['opening_balance'] ?? 0,
            'current_balance' => $account_request['current_balance'] ?? $account_request['opening_balance'] ?? 0,
            'is_active' => $account_request['is_active'] ?? true,
            'description' => $account_request['description'] ?? null,
        ];

        return $account_data;
    }

    public function updateAccountBalance(
        array $request_info,
        string $request_type
    ): FinancialAccount {

        $allowedRequestTypes = [
            'asset_expense',
            'transaction_expense',
            'transaction_revenue',
            'purchase',
            'employee_payment',
        ];

        if (!in_array($request_type, $allowedRequestTypes, true)) {
            throw new \InvalidArgumentException(
                "Unsupported request type: {$request_type}"
            );
        }

        $account = $this->getDetails($request_info['financial_account_id']);

        $amount = $request_info['amount'];

        $newBalance = $request_type === 'transaction_revenue'
            ? $account->current_balance + $amount
            : $account->current_balance - $amount;

        return $this->repository->updateAccountBalance(
            $account,
            [
                'current_balance' => $newBalance,
            ]
        );

    }

}
