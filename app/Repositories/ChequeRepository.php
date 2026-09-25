<?php

namespace App\Repositories;

use App\Models\Cheque;
use Illuminate\Database\Eloquent\Collection;

class ChequeRepository
{
    public function getAll()
    {
        return Cheque::with('financialAccount')->get();
    }

    public function getDetails(Cheque $cheque): ?Cheque
    {
        return $cheque->load('financialAccount');
    }

    public function create(array $cheque_request): Cheque
    {
        return Cheque::create($cheque_request);
    }

    public function update(
        Cheque $cheque,
        array $cheque_request
    ): Cheque {
        $cheque->update($cheque_request);

        return $cheque->refresh()->load('financialAccount');
    }

    public function delete(Cheque $cheque): bool
    {
        return $cheque->delete();
    }

    public function chequeNumberExists(
        string $chequeNumber,
        int $financialAccountId,
        ?int $exceptId = null
    ): bool {
        return Cheque::query()
            ->where('cheque_number', $chequeNumber)
            ->where(
                'financial_account_id',
                $financialAccountId
            )
            ->when(
                $exceptId,
                fn ($query) =>
                    $query->where('id', '!=', $exceptId)
            )
            ->exists();
    }
}
