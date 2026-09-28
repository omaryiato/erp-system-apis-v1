<?php

namespace App\Repositories\Asset;

use App\Models\Asset\AssetExpense;
use Illuminate\Database\Eloquent\Collection;

class AssetExpenseRepository
{

    public function getAll()
    {
        return AssetExpense::with([
                'asset',
                'supplier',
                'financialAccount',
                'cheque',
            ])
            ->orderBy('expense_date', 'desc')
            ->get();
    }

    public function getDetails(AssetExpense $assetExpense): AssetExpense
    {
        return $assetExpense->load([
                'asset',
                'supplier',
                'financialAccount',
                'cheque',
            ]);
    }

    public function create(array $expense_request): AssetExpense
    {
        return AssetExpense::create($expense_request);
    }

    public function update(
        AssetExpense $assetExpense,
        array $expense_request
    ): AssetExpense {

        $assetExpense->update($expense_request);

        return $assetExpense->refresh();
    }

    public function delete(AssetExpense $assetExpense): bool
    {
        return $assetExpense->delete();
    }

}
