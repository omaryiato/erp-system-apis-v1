<?php

namespace App\Repositories;

use App\Models\FinancialAccount;

class FinancialAccountRepository
{
    public function getAll()
    {
        return FinancialAccount::with('cheques')->get();
    }

    public function getDetails(FinancialAccount $account): ?FinancialAccount
    {
        return $account->load('cheques');
    }

    public function create(array $account_request): FinancialAccount
    {
        return FinancialAccount::create($account_request);
    }

    public function update(
        FinancialAccount $account,
        array $account_request
    ): FinancialAccount {
        $account->update($account_request);

        return $account->refresh();
    }

    public function delete(FinancialAccount $account): bool
    {
        return $account->delete();
    }

    public function hasCheques(FinancialAccount $account): bool
    {
        return $account->cheques()->exists();
    }
}
